<?php
namespace GBAF\Controller;

use GBAF\App;
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
        $query = $db->handler->prepare('SELECT * FROM `partner` WHERE `id` = :id');
        $query->execute([':id' => $id]);
        $partner = $query->fetch();
        if (!$partner) {
            App::notFound();
        }
        (new PartnerTemplate())->render($partner);
    }
}
