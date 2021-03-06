<?php
/**
 * Login English lexicon topic
 *
 * @language en
 * @package modx
 * @subpackage lexicon
 */
$_lang['login_language'] = 'Langue';
$_lang['login_activation_key_err'] = 'La clé d\'activation ne correspond pas ! Veuillez vérifier votre email d\'activation et assurez-vous d\'avoir chargé la bonne URL.';
$_lang['login_blocked_admin'] = 'Vous avez été bloqué depuis le gestionnaire par un administrateur.';
$_lang['login_blocked_error'] = 'Vous êtes temporairement bloqué, vous ne pouvez plus vous connecter actuellement. Veuillez réessayer ultérieurement.';
$_lang['login_blocked_ip'] = 'Vous n\'êtes pas autorisé à vous connecter à partir de cette adresse IP.';
$_lang['login_blocked_time'] = 'Vous n\'êtes pas autorisé à vous identifier actuellement. Veuillez réessayer ultérieurement.';
$_lang['login_blocked_too_many_attempts'] = 'Vous avez été bloqué en raison de trop nombreuses tentatives de connexion échouées.';
$_lang['login_button'] = 'Connexion';
$_lang['login_cannot_locate_account'] = 'L\'identifiant ou le mot de passe saisi est incorrect. Veuillez vérifier l\'identifiant, entrer à nouveau le mot de passe, puis réessayer.';
$_lang['login_copyright'] = '&copy; 2005-[[+current_year]] par <a href="https://modx.com/about/company" target="_blank">MODX, LLC</a>. MODX Revolution&trade; est sous licence GPLv2 ou ultérieure.';
$_lang['login_email_label'] = 'Compte email:';
$_lang['login_err_unknown'] = 'Une erreur inconnue s\'est produite pendant la tentative de connexion.';
$_lang['login_forget_your_login'] = 'Vous avez oublié votre mot de passe ?';
$_lang['login_forget_your_login_note'] = 'Pour définir un nouveau mot de passe, entrez votre nom d’utilisateur ou votre email ci-dessous. Si vous avez un compte, un lien de vérification sera envoyé à votre adresse e-mail.';
$_lang['login_new_password'] = 'Nouveau mot de passe';
$_lang['login_new_password_note'] = 'Veuillez confirmer votre nouveau mot de passe en l\'entrant deux fois.<br><br>&bull;&nbsp;La longueur de mot de passe minimale est de <strong>,[[+length]]</strong> caractères.';
$_lang['login_confirm_password'] = 'Confirmer le mot de passe';
$_lang['login_back_to_login'] = 'Retour à la page d\'identification';
$_lang['login_hostname_error'] = 'Votre nom d\'hôte ne pointe pas vers votre adresse IP.';
$_lang['login_message'] = 'Veuillez vous identifier afin de démarrer une session sur votre gestionnaire. Votre identifiant et votre mot de passe sont sensibles à la casse, entrez-les donc avec précaution!';
$_lang['login_password'] = 'Mot de passe';
$_lang['login_password_reset_act_sent'] = 'Si l’utilisateur ou l’e-mail existe, vous recevrez un courriel sous peu.';
$_lang['login_remember'] = 'Gardez-moi connecté pour [[+lifetime]]';
$_lang['login_send_activation_email'] = 'Envoyer un e-mail d\'activation';
$_lang['login_title'] = 'Connexion';
$_lang['login_user_err_nf_email'] = 'Si l’utilisateur ou l’e-mail existe, vous recevrez un courriel sous peu.';
$_lang['login_username'] = 'Nom d\'utilisateur';
$_lang['login_username_or_email'] = 'Nom d\'utilisateur ou E-mail';
$_lang['login_username_password_incorrect'] = 'L\'identifiant ou le mot de passe saisi est incorrect. Veuillez vérifier l\'identifiant, entrer à nouveau le mot de passe, puis réessayer.';
$_lang['login_user_inactive'] = 'Votre compte utilisateur a été désactivé. Veuillez contacter votre administrateur système pour activer le compte.';
$_lang['login_email_subject'] = 'Vos informations de connexion';
$_lang['login_magiclink_subject'] = 'Votre lien de connexion unique';
$_lang['login_magiclink_err'] = 'Votre lien de connexion n\'est pas valide. Veuillez en demander un nouveau.';
$_lang['login_magiclink_email'] = '<h2>Lien de connexion unique</h2><p>Voici votre lien pour vous connecter au gestionnaire de MODX. Ce lien est valide pour la prochaine [[+expiration]].</p><p class="center"><a href="[[+url_scheme]][[+http_host]][[+manager_url]]?magiclink=[[+hash]]" class="btn">Connectez-moi</a></p><p class="small">Si vous n\'avez pas envoyé cette demande, veuillez ignorer ce courriel.</p>';
$_lang['login_magiclink_default_msg'] = 'Si votre e-mail <i>[[+email]]</i> est enregistré avec un compte, vous recevrez un e-mail sous peu.';
$_lang['login_magiclink_error_msg'] = 'Le système n\'a pas pu envoyer un lien de connexion par e-mail. Veuillez contacter l\'administrateur du site si cette erreur persiste.';
$_lang['login_forgot_email'] = '<h2>vous avez oublié votre mot de passe ?</h2><p>nous avons reçu une demande de changement de votre mot de passe de MODX Revolution. Vous pouvez réinitialiser votre mot de passe en cliquant sur le bouton ci-dessous et en suivant les instructions à l’écran.</p><p class="center"><a href="[[+url_scheme]][[+http_host]][[+manager_url]]?modhash=[[+hash]]" class="btn">réinitialiser mon mot de passe</a></p><p class="small">Si vous n’avez pas envoyé cette demande, Merci d\'ignorer ce message.</p>';
$_lang['login_signup_email'] = '<p>Bonjour, [[+username]]!</p><p>Un compte a été enregistré pour vous sur le site web <strong>[[++ site_name]]</strong>. Si vous ne connaissez pas votre mot de passe, [[++ allow_manager_login_forgot_password:is=`1`:then=`réinitialiser le à l’aide du lien mot de passe publié sur l’écran de connexion`:else= `demandez à votre administrateur de site`]].</p><p class="center"><a href="[[+url_scheme]][[+http_host]][[+manager_url]]" class="btn">Connectez-vous à [[++ site_name]]</a></p>';
$_lang['login_greeting_morning'] = '<strong>Bonjour</strong>, Bienvenue !';
$_lang['login_greeting_afternoon'] = '<strong>Bon après-midi</strong>, Bienvenue !';
$_lang['login_greeting_evening'] = '<strong>Bonsoir</strong>, Bienvenue !';
$_lang['login_greeting_night'] = '<strong>Bonne nuit</strong>, Bienvenue !';
$_lang['login_note'] = 'Connectez-vous pour accéder au gestionnaire.';
$_lang['login_note_passwordless'] = 'Veuillez entrer votre adresse e-mail pour recevoir un lien de connexion unique.';
$_lang['login_magiclink_email_button'] = 'Envoyez-moi un lien de connexion unique';
$_lang['login_magiclink_email_placeholder'] = 'Courriel de votre compte utilisateur';
$_lang['login_email'] = 'E-mail';
$_lang['login_help_button_text'] = 'Aide';
$_lang['login_help_title'] = 'Obtenir de l’aide sur MODX';
$_lang['login_help_text'] = '<p>avez-vous besoin de support professionnel MODX ? Nous avons créé un répertoire de professionnels MODX du monde entier qui seront heureux de vous aider. <a href="https://modx.com/professionals/" target="_blank" rel="noopener" title="FTrouver des professionnels sur le site web de MODX">Trouver de l’aide sur modx.com</a>.</p>';
$_lang['login_return_site'] = 'Retourner au site';

