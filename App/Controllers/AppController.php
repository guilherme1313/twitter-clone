<?php
namespace App\Controllers;

//os recursos do miniframework
use MF\Controller\Action;
use MF\Model\Container;

class AppController extends Action {
    public function timeline(){

        $this->validaAutenticacao();
        //recuperação dos tweets
        $tweet = Container::getModel('Tweet');
        $tweet->__set('id_usuario', $_SESSION['id']);
        $tweets = $tweet->getAll();
        $this->view->tweets =  $tweets;

        $usuario = Container::getModel('Usuario');
        $usuario->__set('id', $_SESSION['id']);
        $this->view->info_usuario = $usuario->getInfoUsuario();
        $this->view->total_tweets = $usuario->getTotalTweets();
        $this->view->total_seguindo = $usuario->getTotalSeguindo();
        $this->view->total_seguidores = $usuario->getTotalSeguidores();

        $this->render('timeline');


    }

    public function tweet(){

        $this->validaAutenticacao();
        $tweet = Container::getModel('Tweet');
        $tweet->__set('id_usuario', $_SESSION['id']);
        $tweet->__set('tweet', $_POST['tweet']);
        $tweet->salvar();
        header('Location: /timeline');





    }

    public function validaAutenticacao(){
        session_start();
        if(!isset($_SESSION['id']) || empty($_SESSION['id']) || !isset($_SESSION['nome']) || empty($_SESSION['nome'])){
            header('Location: /?login=erro');
        }
    }

    public function quemSeguir() {

        $this->validaAutenticacao();

        $pesquisarPor = isset($_GET['pesquisarPor']) ? $_GET['pesquisarPor'] : '';

        $usuarios = array();
        $usuario = Container::getModel('Usuario');
        $usuario->__set('id', $_SESSION['id']);
        if($pesquisarPor != '') {


            $usuario->__set('nome', $pesquisarPor);
            $usuarios = $usuario->getAll();

        }

        $this->view->usuarios = $usuarios;

        $this->view->info_usuario = $usuario->getInfoUsuario();
        $this->view->total_tweets = $usuario->getTotalTweets();
        $this->view->total_seguindo = $usuario->getTotalSeguindo();
        $this->view->total_seguidores = $usuario->getTotalSeguidores();

        $this->render('quemSeguir');
    }

    public function acao(){
        $this->validaAutenticacao();
        $acao_feita = isset($_GET['acao']) ? $_GET['acao'] : '';
        $id_usuario_seguindo = isset($_GET['id_usuario']) ? $_GET['id_usuario'] : '';
        $acao = Container::getModel('Acao');
        $acao->__set('id_usuario', $_SESSION['id']);
        $acao->__set('id_usuario_seguindo', $id_usuario_seguindo);

        if($acao_feita == 'seguir'){
            $acao->seguirUsuario();
        }else if($acao_feita == 'deixar_de_seguir'){
            $acao->deixarSeguirUsuario();
        }

        header('Location: /quem_seguir');

    }

    public function deletar_tweet(){
        $this->validaAutenticacao();
        $tweet = Container::getModel('Tweet');
        $id = (isset($_GET['id_tweet']) && !empty($_GET['id_tweet']) && is_numeric($_GET['id_tweet']) ? $_GET['id_tweet'] : null);
        $tweet->__set('id', $id);
        $tweet->deleta_tweet();
        header('Location: /timeline');
    }
}