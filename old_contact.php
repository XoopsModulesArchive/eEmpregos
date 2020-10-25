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

        // $message .= "--------------- "._JOBS_STARTMESS." $namep -------------------\n";

        // $message .= "$messtext\n\n";

        // $message .= "--------------- "._JOBS_ENDMESS." de $namep -------------------\n\n";

        // $message .= ""._JOBS_CANJOINT." $namep "._JOBS_TO." $post $teles";

        $message = '' . _JOBS_MESSFROM . " $namep   $post   " . $meta['title'] . "\n\n";

        $message .= '' . _JOBS_REMINDANN . " $type : $title\n" . _JOBS_MESSAGETEXT . " : $description\n\n";

        $message .= '--------------- ' . _JOBS_STARTMESS . " $namep " . _JOBS_FROMANNOF . "-------------------\n";

        $message .= "$messtext\n\n";

        $message .= '--------------- ' . _JOBS_ENDMESS . " -------------------\n\n";

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
    }

    redirect_header('index.php', 1, _JOBS_MESSEND);

    exit();
}
    $lid = $_GET['lid'] ?? '';

    include 'header.php';
    require XOOPS_ROOT_PATH . '/header.php';
    OpenTable();

    echo '<script>
          function verify() {
                var msg = "' . _JOBS_VALIDERORMSG . '\\n__________________________________________________\\n\\n";
                var errors = "FALSE";

			
				if (document.Cont.namep.value == "") {
                        errors = "TRUE";
                        msg += "' . _JOBS_VALIDSUBMITTER . '\\n";
                }
				
				if (document.Cont.post.value == "") {
                        errors = "TRUE";
                        msg += "' . _JOBS_VALIDEMAIL . '\\n";
                }
				
				if (document.Cont.messtext.value == "") {
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

    echo '<B>' . _JOBS_CONTACTAUTOR . '</B><br><br>';
    echo '' . _JOBS_TEXTAUTO . '<br>';
    echo '<form onSubmit="return verify();" method="post" action="contact.php" NAME="Cont">';
    echo "<INPUT TYPE=\"hidden\" NAME=\"id\" VALUE=\"$lid\">";
    echo '<INPUT TYPE="hidden" NAME="submit" VALUE="1">';

    if ($xoopsUser) {
        $idd = $xoopsUser->getVar('name', 'E');

        $idde = $xoopsUser->getVar('email', 'E');
    }

    echo "<TABLE width='100%' class='outer' cellspacing='1'>
    <TR>
      <TD class='head'>" . _JOBS_YOURNAME . "</TD>
      <TD class='even'><input type=\"text\" name=\"namep\" size=\"32\" value=\"$idd\"></TD>
    </TR>
    <TR>
      <TD class='head'>" . _JOBS_YOUREMAIL . "</TD>
      <TD class='even'><input type=\"text\" name=\"post\" size=\"32\" value=\"$idde\"></font></TD>
    </TR>
    <TR>
      <TD class='head'>" . _JOBS_YOURPHONE . "</TD>
      <TD class='even'><input type=\"text\" name=\"tele\" size=\"32\"></font></TD>
    </TR>
    <TR>
      <TD class='head'>" . _JOBS_YOURMESSAGE . "</TD>
      <TD class='even'><textarea rows=\"5\" name=\"messtext\" cols=\"30\"></textarea></TD>
    </TR>
	</TABLE><br>
      <p><INPUT TYPE=\"submit\" VALUE=\"" . _JOBS_SENDFR . '">
	</form>';

    CloseTable();
    require XOOPS_ROOT_PATH . '/footer.php';
