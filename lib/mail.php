<?php

// TODO: DA SISTEMARE

// EMAIL CONFERMA REGISTRAZIONE
function reg_send_mail_ok($to){
  global $_CONFIG;
  $user = get_user_by_email($to);
  $oggetto = 'Donordonee - Registrazione confermata';
  $messaggio = '
  <html>
    <body>
      <table style="background-color:#E6E6E6;width:100%;" cellspacing="0" cellpadding="0" border="0">
        <tr>
          <td align="center" style="padding:15px 0;">
            <table width="550" cellspacing="0" cellpadding="0" border="0" style="background-color:#FFFFFF;border:1px solid #999999;box-shadow:0 0 5px #999999;font-size:14px;font-family:Open-Sans,Helvetica,Arial;">
              <tr>
                <td colspan="3" heigth="16">&nbsp;</td>
              </tr>
              <tr>
                <td width="15"></td>
                <td width="520" height="100" align="center" valign="top"><img src="'.$_CONFIG['site_path'].'/images/new/logo-mail.png" alt="Donordonee" /></td>
                <td width="15"></td>
              </tr>
              <tr>
                <td></td>
                <td>
                  <p style="color:#000;margin: 0 0 5px 0;">Gentile</p>
                  <p style="color:#000;font-size:20px;margin: 0 0 5px 0;"><strong>'.$user["nome"].'</strong></p>
                  <p style="color:#000;margin: 0 0 10px 0;">ti diamo il nostro benvenuto su Donordonee.<br>Da questo momento potrai accedere al tuo account con le credenziali scelte in fase di registrazione.<br>Potrai cambiare la password in qualsiasi momento.</p>
                  <hr />
                  <h3 style="font-size:20px;margin:0;text-align:center"><strong>Cosa puoi fare ora?</strong></h3>
                  <hr />
                  <p style="color:#000;font-size:18px;margin: 5px 0;"><strong>Descrivi il sogno, o il tuo progetto personale, che vuoi realizzare.</strong></p>
                  <p style="color:#000;margin: 0 0 10px 0;">Per poter partecipare al "Gioco del dono" dovrai aver completato il tuo profilo con le informazioni indispensabili per permettere agli altri di sapere a chi stanno donando e perch&eacute;.</p>
                  <hr />
                  <p style="color:#000;font-size:18px;margin: 5px 0;"><strong>Prova gratuitamente.</strong></p>
                  <p style="color:#000;margin: 0 0 10px 0;">In questo momento Donordonee &egrave; in versione sperimentale e le donazioni, nel Gioco del dono, saranno tutte simulate, ovvero non avverr&agrave; alcun reale trasferimento di denaro. Perci&ograve; ora non &egrave; necessario disporre di un conto PayPal.</p>
                  <p style="color:#000;margin: 0 0 10px 0;">Se lo vorrai, potrai comunque sostenere i tuoi progetti preferiti: nelle schede descrittive delle organizzazioni e dei loro progetti potrai trovare le informazioni necessarie.</p>
                  <hr />
                  <p style="color:#000;font-size:18px;margin: 5px 0;"><strong>Invita i tuoi contatti.</strong></p>
                  <p style="color:#000;margin: 0 0 10px 0;">Donordonee pu&ograve; crescere anche e soprattutto grazie a te. Pi&ugrave; persone partecipano al Gioco del dono, pi&ugrave; donazioni arrivano ai progetti non-profit iscritti e pi&ugrave; sogni possono essere realizzati.</p>
                  <hr />
                </td>
                <td></td>
              </tr>
              <tr>
                <td></td>
                <td colspan="2">
                  <table style="font-size:14px;" cellspacing="0" cellpadding="0" border="0">
                    <tr>
                      <td colspan="2">
                        <h3 style="font-size:20px;margin:0;text-align:center"><strong>Vuoi registrare un\'organizzazione?</strong></h3>
                      </td>
                    </tr>
                    <tr>
                      <td valign="top" style="padding-top:50px;">
                        <p style="color:#000;margin: 0 0 10px 0;">Se sei responsabile della raccolta fondi per uno o pi&ugrave; organizzazioni non-profit, nel tuo <strong>Profilo</strong>, alla voce "<strong>Le tue organizzazioni</strong>", puoi:</p>
                        <ul style="margin: 0 0 0 15px;padding:0;">
                          <li style="margin:0; padding:0">
                            <p style="color:#000;margin: 0 0 10px 0;">registrare e gestire tutte le organizzazioni e i progetti che vuoi;</p>
                          </li>
                          <li style="margin:0; padding:0">
                            <p style="color:#000;margin: 0 0 10px 0;">autorizzare altri utenti. o essere autorizzato da altri utenti, a gestire una o pi&ugrave; organizzazioni, o anche soltanto singoli progetti.</p>
                          </li>
                        </ul>
                      </td>
                      <td>
                        <img src="'.$_CONFIG['site_path'].'/images/new/img-mail-onp.png" alt="Donordonee" />
                      </td>
                    </tr>
                    <tr>
                      <td colspan="2">
                        <hr />
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>
              <tr>
                <td></td>
                <td>
                  <p style="color:#000;margin: 0 0 10px 0;">A presto!<br><strong>Il team di Donordonee</strong></p>
                </td>
                <td></td>
              </tr>
              <tr>
                <td colspan="3" heigth="16">&nbsp;</td>
              </tr>
            </table>
          </td>
        </tr>
      </table>
    </body>
  </html>';
  $headers  = 'MIME-Version: 1.0' . "\r\n";
  $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
  $headers .= 'To: '.$to.' <'.$to.'>' . "\r\n";
  $headers .= 'From: '.$_CONFIG['email_sender2'].' <'.$_CONFIG['email_sender2'].'>' . "\r\n";
  $headers .= 'Bcc: gennaro.ciotola@donordonee.eu' . "\r\n";
  mail($to, $oggetto, $messaggio, $headers);
  // EMAIL AD ASSISTENZA PER INSERIMENTO SU MAILCIMP
  $to2 = $_CONFIG['email_sender2'];
  $headers2 = 'MIME-Version: 1.0' . "\r\n";
  $headers2 .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
  $headers2 .= 'To: '.$to2.' <'.$to2.'>' . "\r\n";
  $headers2 .= 'From: '.$_CONFIG['email_sender'].' <'.$_CONFIG['email_sender'].'>' . "\r\n";
  $headers2 .= 'Bcc: gennaro.ciotola@donordonee.eu' . "\r\n";
  mail($to2, "Nuovo Utente iscritto", "{$user["nome"]} {$user["cognome"]} si &egrave; registrato con la seguente email: {$to}", $headers2);
}

// EMAIL PER REGISTRAZIONE
function reg_send_confirmation_mail($to, $from, $id, $type, $name=false){
  global $_CONFIG;
  $oggetto = 'Abbiamo ricevuto la tua richiesta di iscrizione a Scout';
  $text_top = "Abbiamo ricevuto la tua richiesta di iscrizione a Scout.";
  $messaggio = '
  <html>
    <body>
      <p style="margin:30px 0;text-align:center;"><img src="'.$_CONFIG['site_path'].'/images/new/logo-mail.png" alt="Donordonee" /></p>
      <p style="color:#000;font-size: 13px;margin: 0 0 20px 0;">'.$text_top.'<br>Per verificare questa richiesta, fai clic sul link riportato di seguito.</p>
      <p style="color:#000;font-size: 13px;margin: 0 0 20px 0;"><a style="color:#9F512C;text-decoration:none;" href="'.$_CONFIG['site_path'].'confirm/'.$id.'/'.$type.'" title="'.$_CONFIG['site_path'].'confirm/'.$id.'/'.$type.'">'.$_CONFIG['site_path'].'confirm/'.$id.'/'.$type.'</a></p>
      <p style="color:#000;font-size: 13px;margin: 0 0 20px 0;">Se il link sopra indicato non funziona, copia l\'URL e incollalo in una nuova finestra del browser.</p>
      <p style="color:#000;font-size: 13px;margin: 0 0 20px 0;">Se hai ricevuto questo messaggio per errore, non &egrave; richiesta alcuna azione da parte tua. Se non fai clic sul link, la richiesta di registrazione decadr&agrave;.</p>
      <p style="color:#000;font-size: 13px;margin: 0 0 20px 0;">Cordiali saluti,<br><strong>Il team di Donordonee</strong></p>
    </body>
  </html>';
  $headers  = 'MIME-Version: 1.0' . "\r\n";
  $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
  $headers .= 'To: '.$to.' <'.$to.'>' . "\r\n";
  $headers .= 'From: '.$from.' <'.$from.'>' . "\r\n";

  if(@mail($to, $oggetto, $messaggio, $headers))
    return "OK";
  else
    return "KO";
}

// CAMBIO PASSWORD
function change_password_send_mail($to, $from, $new_pass){
  //invio la mail di conferma
  $msg = "La tua password &egrave; stata cambiata con successo.
Di seguito trovi la nuova password:
{$new_pass}";
  return (mail($to, "Scout - Conferma cambio password", $msg, "From: ".$from)) ? "OK" : "KO";
}