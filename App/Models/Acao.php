<?php
namespace App\Models;

use MF\Model\Model;

class Acao extends Model
{
    private $id;
    private $id_usuario;
    private $id_usuario_seguindo;

    public function __get($atributo) {
        return $this->$atributo;
    }

    public function __set($atributo, $valor) {
        $this->$atributo = $valor;
    }
    public function seguirUsuario(){
        $query =  "INSERT INTO usuarios_seguidores(id_usuario, id_usuario_seguindo)VALUES(:id_usuario, :id_usuario_seguindo)";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id_usuario', $this->__get('id_usuario'));
        $stmt->bindValue(':id_usuario_seguindo', $this->__get('id_usuario_seguindo'));
        $stmt->execute();
        return true;
    }
    public function deixarSeguirUsuario(){
        $query =  "DELETE FROM usuarios_seguidores WHERE id_usuario = :id_usuario AND id_usuario_seguindo = :id_usuario_seguindo";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id_usuario', $this->__get('id_usuario'));
        $stmt->bindValue(':id_usuario_seguindo', $this->__get('id_usuario_seguindo'));
        $stmt->execute();
        return true;
    }
}