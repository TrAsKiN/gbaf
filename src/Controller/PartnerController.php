<?php
namespace GBAF\Controller;

use GBAF\App;
use GBAF\Controller;
use GBAF\Database;
use GBAF\Template;
use GBAF\Template\PartnerTemplate;

class PartnerController extends Controller
{
    /**
     * @param int $id
     * @return Template
     */
    public function partner($id): Template
    {
        $db = new Database();
        $query = $db->handler->prepare('SELECT * FROM `partner` WHERE `id` = :id');
        $query->execute([':id' => $id]);
        $partner = $query->fetch();
        if (!$partner) {
            App::notFound();
        }
        return (new PartnerTemplate())->render($partner);
    }
}
