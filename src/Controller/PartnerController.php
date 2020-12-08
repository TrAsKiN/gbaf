<?php
namespace GBAF\Controller;

use GBAF\Controller;
use GBAF\Database;
use GBAF\Template\PartnerTemplate;

class PartnerController extends Controller
{
    /**
     * @param int $id
     * @return void
     */
    public function partner($id): void
    {
        $db = new Database();
        $query = $db->handler->prepare('SELECT * FROM `acteur` WHERE `id_acteur` = :id');
        $query->execute([':id' => $id]);
        $partner = $query->fetch();
        (new PartnerTemplate())->render($partner);
    }
}
