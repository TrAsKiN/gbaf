<?php
namespace GBAF\Controller;

use GBAF\App;
use GBAF\Controller;
use GBAF\Template;
use GBAF\Template\PartnerTemplate;

class PartnerController extends Controller
{
    public function partner(int $id): Template
    {
        $user = $this->db->getUserByUsername($_SESSION['username']);
        $partner = $this->db->getPartner($id);
        if (!$partner) {
            App::notFound();
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
            App::redirect('/partner-' . $partner['id']);
        }
        return (new PartnerTemplate())->render([
            'partner' => $partner,
            'grades' => $grades,
            'comments' => $comments,
            'user' => $user
        ]);
    }
}
