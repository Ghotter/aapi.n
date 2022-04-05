<?php
require_once('../model/operacao.php');

function isTheseParametersAvailable(){
        $available= true;
        $missingparams= "";
        foreach($params as $param){
if(!isset($_POST[$param]) || strlen($_POST[$param])<=0){
    $available= false;
    $missingparams = $missingparams.", ". $param;
}
}
if(!$available){
    $response= array();
    $response['error']= true;
    $response['message'] = 'Parameters' .substr($missingparams, 1, strlen($missingparams)).' missing';
    echo json_encode($response);

    die();
}
}
$response= array();
if(isset($_GET['apicall'])){
    switch($_GET['apicall']){
        case 'createFruta':
            isTheseParametersAvailable(array('campo_2', 'campo_3','campo_4'));

            $db = new Operacao();

            $result = $db ->createFruta(
                $_POST['campo_2'],
                $_POST['campo_3'],
                $_POST['campo_4']
            );
        if ($result){
            $response['error'] = false;
            $response['message']= 'dados inseridos com sucesso.';
            $response['dadoscreate'] = $db ->getFrutas();
        }else{
            $response['error']= true;
            $response['message'] = 'dados nao foram inseridos.';

        }
        break;
        case 'getFrutas':
            $db = new Operacao();
            $response['error'] = false;
            $response['message']= 'dados listados com sucesso';
            $response['dadoslista']=$db ->getFrutas();

        break;
        case 'updateFrutas':
            isTheseParametersAvailable(array('campo_1','campo_2','campo_3','campo_4'));
            $db =new Operacao();
            $result = $db->updateFrutas(
                $_POST['campo_1'],
                $_POST['campo_2'],
                $_POST['campo_3'],
                $_POST['campo_4']
            );
            if($result){
                $response['error'] = false;
                $response['message'] = 'dados alterados com sucesso.';
                $response['deleteFrutas'] = $db->getFrutas();
            }else{
                $response['error'] = true;
                $response['message'] = 'dados nao alterados.';
            }
            break;
            case 'deleteFrutas':
                if(isset($_GET['uid'])){
                    $response['error'] =  false;
                    $response['message']='dados excluidos com sucesso';
                    $response['deleteFrutas']= $db->getFrutas();
                }else{
                    $response['error'] = true;
                    $response['message'] = 'algo deu errado';
                }
            }else{
                    $response['error'] = true;
                    $response['message'] = 'chamada de api com defeito.';
                }
                break;

    }
}else{
    $response['error']= true;
    $response['message']= 'chamada de api com defeito';
}
echo json_decode($response);