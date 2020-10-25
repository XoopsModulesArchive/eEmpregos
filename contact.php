<?php

// $Id: v 1.00 2004/12/30 15:46:00 Gustavo S. Villa Exp
//  ------------------------------------------------------------------------ //
//                            e-Empregos                                     //
//                              E-WARE                                       //
//                   <http://www.e-ware.com.br>                             //
//  ------------------------------------------------------------------------ //
//  Você ainda não pode substituir ou alterar qualquer parte desses comentários    //
//  ou créditos dos titulares e autores os quais são considerados direitos   //
//  reservados.                                                              //
//  ------------------------------------------------------------------------ //
//  Autor: Gustavo S. Villa  <guvilladev@e-ware.com.br>                      //
//  ------------------------------------------------------------------------ //
foreach ($_POST as $k => $v) {
    ${$k} = $v;
}

if ($submit) {
    include 'header.php';

    global $xoopsConfig, $xoopsDB, $myts, $meta;

    $result = $xoopsDB->query('select email, submitter, title, type, company, description, requirements FROM  ' . $xoopsDB->prefix('eEmpregos_listing') . " WHERE lid = '$id'");

    while (list($email, $submitter, $title, $type, $company, $description, $requirements) = $xoopsDB->fetchRow($result)) {
        $title = htmlspecialchars($title, ENT_QUOTES | ENT_HTML5);

        $type = htmlspecialchars($type, ENT_QUOTES | ENT_HTML5);

        $company = htmlspecialchars($company, ENT_QUOTES | ENT_HTML5);

        $description = htmlspecialchars($description, ENT_QUOTES | ENT_HTML5);

        $requirements = htmlspecialchars($requirements, ENT_QUOTES | ENT_HTML5);

        $submitter = htmlspecialchars($submitter, ENT_QUOTES | ENT_HTML5);

        if ($tele) {
            $teles = '' . _JOBS_ORAT . " $tele";
        } else {
            $teles = '';
        }

        // Specification for Japan:

        // $message .= ""._JOBS_MESSFROM." $namep "._JOBS_FROMANNOF." ".$meta['title']."\n\n";

        // $message .= ""._JOBS_REMINDANN."\n$type : $titre\nTexte : $description\n\n";

        // $message .= "--------------- "._JOBS_STARTMESS." $namep -------------------\n\n";

        // $message .= "$messtext\n\n";

        // $message .= "--------------- "._JOBS_ENDMESS." de $namep -------------------\n\n";

        // $message .= ""._JOBS_CANJOINT." $namep "._JOBS_TO." $post $teles";

        $message .= '' . _JOBS_REMINDANN . " $type : $title " . _JOBS_FROMANNOF . " $sitename\n";

        $message .= '' . _JOBS_MESSFROM . " $namep   $post   " . $meta['title'] . "\n\n";

        $message .= "\n";

        $message .= stripslashes("$messtext\n\n");

        $message .= '   ' . _JOBS_ENDMESS . "\n\n";

        $message .= '' . _JOBS_CANJOINT . " $namep " . _JOBS_TO . " $post $teles \n\n";

        $message .= "End of message \n\n";

        $subject = '' . _JOBS_CONTACTAFTERANN . '';

        $mail = getMailer();

        $mail->useMail();

        //$mail->setFromName($meta['title']);

        $mail->setFromEmail($post);

        $mail->setToEmails($email);

        $mail->setSubject($subject);

        $mail->setBody($message);

        $mail->send();

        echo $mail->getErrors();

        $message .= "\n" . $HTTP_SERVER_VARS['REMOTE_ADDR'] . "\n";

        $adsubject = $xoopsConfig['sitename'] . ' Job Reply ';

        $xoopsMailer = getMailer();

        $xoopsMailer->useMail();

        $xoopsMailer->setToEmails($xoopsConfig['adminmail']);

        $xoopsMailer->setFromEmail($xoopsConfig['adminmail']);

        $xoopsMailer->setFromName($xoopsConfig['sitename']);

        $xoopsMailer->setSubject($adsubject);

        $xoopsMailer->setBody($message);

        $xoopsMailer->send();
    }

    redirect_header('index.php', 1, _JOBS_MESSEND);

    exit();
}
    $lid = $_GET['lid'] ?? '';

    include 'header.php';
    require XOOPS_ROOT_PATH . '/header.php';
    OpenTable();

    echo '<script type="text/javascript">
          function verify() {
                var msg = "' . _JOBS_VALIDERORMSG . '\\n__________________________________________________\\n\\n";
                var errors = "FALSE";

			
				if (document.cont.namep.value == "") {
                        errors = "TRUE";
                        msg += "' . _JOBS_VALIDSUBMITTER . '\\n";
                }
				
				if (document.cont.post.value == "") {
                        errors = "TRUE";
                        msg += "' . _JOBS_VALIDEMAIL . '\\n";
                }
				
				if (document.cont.messtext.value == "") {
                        errors = "TRUE";
                        msg += "' . _JOBS_VALIDMESS . '\\n";
                }
				
  
                if (errors == "TRUE") {
                        msg += "__________________________________________________\\n\\n' . _JOBS_VALIDMSG . '\\n";
                        alert(msg);
                        return false;
                }
          }
          </script>';

    echo '<b>' . _JOBS_CONTACTAUTOR . '</b><br><br>';
    echo '' . _JOBS_TEXTAUTO . '<br>';
    echo '<form onsubmit="return verify();" method="post" action="contact.php" name="cont">';
    echo "<input type=\"hidden\" name=\"id\" value=\"$lid\">";
    echo '<input type="hidden" name="submit" value="1">';

    if ($xoopsUser) {
        $idd = $xoopsUser->getVar('name', 'E');

        $idde = $xoopsUser->getVar('email', 'E');
    }

    echo "<table width='100%' class='outer' cellspacing='1'>
    <tr>
      <td class='head'>" . _JOBS_YOURNAME . "</td>
      <td class='even'><input type=\"text\" name=\"namep\" size=\"40\" value=\"$idd\"></td>
    </tr>
    <tr>
      <td class='head'>" . _JOBS_YOUREMAIL . "</td>
      <td class='even'><input type=\"text\" name=\"post\" size=\"40\" value=\"$idde\"></td>
    </tr>
    <tr>
      <td class='head'>" . _JOBS_YOURPHONE . "</td>
      <td class='even'><input type=\"text\" name=\"tele\" size=\"40\"></td>
    </tr>
    <tr>
      <td class='head'>" . _JOBS_YOURMESSAGE . "</td>
      <td class='even'><textarea rows=\"5\" name=\"messtext\" cols=\"40\"></textarea></td>
    </tr>
	</table>
	<table class='outer'><tr><td>
        <img src=\"" . XOOPS_URL . '/modules/eEmpregos/ip_image.php" alt="IP Address"><br> e está gravado em nosso banco de dados! Ações serão tomadas se houver algum tipo de abuso dentro do sistema.
        </td></tr></table>
	<br>
      <p><input type="submit" value="' . _JOBS_SENDFR . '"></p>
	</form>';

    CloseTable();
    require XOOPS_ROOT_PATH . '/footer.php';
