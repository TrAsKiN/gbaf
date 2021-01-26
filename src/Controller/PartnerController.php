<?php
namespace GBAF\Controller;

use GBAF\App;
use GBAF\Controller;
use GBAF\Template;
use GBAF\Template\PartnerTemplate;

class PartnerController extends Controller
{
    public function partner(int $id): ?Template
    {
        $user = $this->db->getUserByUsername($_SESSION['username']);
        $partner = $this->db->getPartner($id);
        if (!$partner) {
            return App::notFound();
        }
        $grades = $this->db->getGrades($id);
        $comments = $this->db->getComments($id);
        $securedGet = array_map('htmlspecialchars', $_GET);
        if (isset($securedGet['grade'])) {
            if ($this->db->getGradeByUser($partner, $user)) {
                $this->db->updateGrade($securedGet['grade'], $user);
            } else {
                $this->db->addGrade($securedGet['grade'], $partner, $user);
            }
            return App::redirect('/partner-' . $partner['id']);
        }
        $securedPost = array_map('htmlspecialchars', $_POST);
        if (isset($securedPost['text-comment']) && !empty(trim($securedPost['text-comment']))) {
            if (!$this->db->getCommentByUser($partner, $user)) {
                $this->db->addComment($securedPost['text-comment'], $partner, $user);
                App::addFlash("Commentaire enregistré !");
                return App::redirect('/partner-' . $partner['id']);
            } else {
                App::addFlash("Un commentaire est déjà enregistré !");
            }
        }
        return (new PartnerTemplate())->render([
            'partner' => $partner,
            'grades' => $grades,
            'comments' => $comments,
            'user' => $user
        ]);
    }
}
