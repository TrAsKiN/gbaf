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
        $partner = $this->db->getPartner($id);
        if (!$partner) {
            App::notFound();
        }
        $grades = $this->db->getGrades($id);
        $comments = $this->db->getComments($id);
        return (new PartnerTemplate())->render([
            'partner' => $partner,
            'grades' => $grades,
            'comments' => $comments
        ]);
    }
}
