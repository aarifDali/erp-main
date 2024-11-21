<?php

namespace Workdo\Holidayz\Database\Seeders;

use App\Models\EmailTemplate;
use App\Models\EmailTemplateLang;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class EmailTemplateTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $emailTemplate = [
            'New Hotel',
            'New Hotel Customer',
            'New Room Booking Invoice',
            'New Room Booking Invoice Payment',
            'Room Booking Invoice Status Updated',
            'New Room Booking By Hotel Customer',
        ];

        $defaultTemplate = [
            'New Hotel' => [
                'subject' => 'Hotel Detail',
                'variables' => '{
                        "App Name": "app_name",
                        "Company Name": "company_name",
                        "App Url": "app_url",
                        "Hotel Email": "hotel_email",
                        "Hotel Name": "hotel_name",
                        "Hotel Contact": "hotel_contact"
                        }',
                        'lang' => [
                        'ar' => '<p>مرحبا،&nbsp;<br>مرحبا بك في {app_name}.</p><p><b>البريد الإلكتروني </b>: {hotel_email}<br><b>كلمه السر</b> : {hotel_name}</p><p>{app_url}</p><p>شكر،<br>{app_name}</p>',
                        'da' => '<p>Hej,&nbsp;<br>Velkommen til {app_name}.</p><p><b>E-mail </b>: {hotel_email}<br><b>Adgangskode</b> : {hotel_name}</p><p>{app_url}</p><p>Tak,<br>{app_name}</p>',
                        'de' => '<p>Hallo,&nbsp;<br>Willkommen zu {app_name}.</p><p><b>Email </b>: {hotel_email}<br><b>Passwort</b> : {hotel_name}</p><p>{app_url}</p><p>Vielen Dank,<br>{app_name}</p>',
                        'en' => '<p>Hello,&nbsp;<br />Welcome to {app_name}</p>
                        <p><strong>Email </strong>: {hotel_email}<br /><strong>hotel_name</strong> : {hotel_name}</p>
                        <p>{app_url}</p>
                        <p>Thanks,<br />{app_name}</p>',
                        'es' => '<p>Hola,&nbsp;<br>Bienvenido a {app_name}.</p><p><b>Correo electrónico </b>: {hotel_email}<br><b>Contraseña</b> : {hotel_name}</p><p>{app_url}</p><p>Gracias,<br>{app_name}</p>',
                        'fr' => '<p>Bonjour,&nbsp;<br>Bienvenue à {app_name}.</p><p><b>Email </b>: {hotel_email}<br><b>Mot de passe</b> : {hotel_name}</p><p>{app_url}</p><p>Merci,<br>{app_name}</p>',
                        'it' => "<p>Ciao,&nbsp;<br>Benvenuto a {app_name}.</p><p><b>E-mail </b>: {hotel_email}<br><b>Parola d'ordine</b> : {hotel_name}</p><p>{app_url}</p><p>Grazie,<br>{app_name}</p>",
                        'ja' => '<p>こんにちは、&nbsp;<br>へようこそ {app_name}.</p><p><b>Eメール </b>: {hotel_email}<br><b>パスワード</b> : {hotel_name}</p><p>{app_url}</p><p>おかげで、<br>{app_name}</p>',
                        'nl' => '<p>Hallo,&nbsp;<br>Welkom bij {app_name}.</p><p><b>E-mail </b>: {hotel_email}<br><b>Wachtwoord</b> : {hotel_name}</p><p>{app_url}</p><p>Bedankt,<br>{app_name}</p>',
                        'pl' => '<p>Witaj,&nbsp;<br>Witamy w {app_name}.</p><p><b>E-mail </b>: {hotel_email}<br><b>Hasło</b> : {hotel_name}</p><p>{app_url}</p><p>Dzięki,<br>{app_name}</p>',
                        'ru' => '<p>Привет,&nbsp;<br>Добро пожаловать в {app_name}.</p><p><b>Электронное письмо </b>: {hotel_email}<br><b>пароль</b> : {hotel_name}</p><p>{app_url}</p><p>Спасибо,<br>{app_name}</p>',
                        'pt' => '<p>Ol&aacute;, Bem-vindo a {app_name}.</p>
                        <p>E-mail: {hotel_email}</p>
                        <p>Senha: {hotel_name}</p>
                        <p>{app_url}</p>
                        <p>&nbsp;</p>
                        <p>Obrigado,</p>
                        <p>{app_name}</p>',
                ],
            ],
            'New Hotel Customer' => [
                'subject' => 'Hotel Customer Login Detail',
                'variables' => '{
                        "App Name": "app_name",
                        "Company Name": "company_name",
                        "App Url": "app_url",
                        "Hotel Customer Email": "hotel_customer_email",
                        "Hotel Customer Password": "hotel_customer_password"
                        }',
                        'lang' => [
                        'ar' => '<p>مرحبا،&nbsp;<br>مرحبا بك في {app_name}.</p><p><b>البريد الإلكتروني </b>: {hotel_customer_email}<br><b>كلمه السر</b> : {hotel_customer_password}</p><p>{app_url}</p><p>شكر،<br>{app_name}</p>',
                        'da' => '<p>Hej,&nbsp;<br>Velkommen til {app_name}.</p><p><b>E-mail </b>: {hotel_customer_email}<br><b>Adgangskode</b> : {hotel_customer_password}</p><p>{app_url}</p><p>Tak,<br>{app_name}</p>',
                        'de' => '<p>Hallo,&nbsp;<br>Willkommen zu {app_name}.</p><p><b>Email </b>: {hotel_customer_email}<br><b>Passwort</b> : {hotel_customer_password}</p><p>{app_url}</p><p>Vielen Dank,<br>{app_name}</p>',
                        'en' => '<p>Hello,&nbsp;<br />Welcome to {app_name}</p>
                        <p><strong>Email </strong>: {hotel_customer_email}<br /><strong>Password</strong> : {hotel_customer_password}</p>
                        <p>{app_url}</p>
                        <p>Thanks,<br />{app_name}</p>',
                        'es' => '<p>Hola,&nbsp;<br>Bienvenido a {app_name}.</p><p><b>Correo electrónico </b>: {hotel_customer_email}<br><b>Contraseña</b> : {hotel_customer_password}</p><p>{app_url}</p><p>Gracias,<br>{app_name}</p>',
                        'fr' => '<p>Bonjour,&nbsp;<br>Bienvenue à {app_name}.</p><p><b>Email </b>: {hotel_customer_email}<br><b>Mot de passe</b> : {hotel_customer_password}</p><p>{app_url}</p><p>Merci,<br>{app_name}</p>',
                        'it' => "<p>Ciao,&nbsp;<br>Benvenuto a {app_name}.</p><p><b>E-mail </b>: {hotel_customer_email}<br><b>Parola d'ordine</b> : {hotel_customer_password}</p><p>{app_url}</p><p>Grazie,<br>{app_name}</p>",
                        'ja' => '<p>こんにちは、&nbsp;<br>へようこそ {app_name}.</p><p><b>Eメール </b>: {hotel_customer_email}<br><b>パスワード</b> : {hotel_customer_password}</p><p>{app_url}</p><p>おかげで、<br>{app_name}</p>',
                        'nl' => '<p>Hallo,&nbsp;<br>Welkom bij {app_name}.</p><p><b>E-mail </b>: {hotel_customer_email}<br><b>Wachtwoord</b> : {hotel_customer_password}</p><p>{app_url}</p><p>Bedankt,<br>{app_name}</p>',
                        'pl' => '<p>Witaj,&nbsp;<br>Witamy w {app_name}.</p><p><b>E-mail </b>: {hotel_customer_email}<br><b>Hasło</b> : {hotel_customer_password}</p><p>{app_url}</p><p>Dzięki,<br>{app_name}</p>',
                        'ru' => '<p>Привет,&nbsp;<br>Добро пожаловать в {app_name}.</p><p><b>Электронное письмо </b>: {hotel_customer_email}<br><b>пароль</b> : {hotel_customer_password}</p><p>{app_url}</p><p>Спасибо,<br>{app_name}</p>',
                        'pt' => '<p>Ol&aacute;, Bem-vindo a {app_name}.</p>
                        <p>E-mail: {hotel_customer_email}</p>
                        <p>Senha: {hotel_customer_password}</p>
                        <p>{app_url}</p>
                        <p>&nbsp;</p>
                        <p>Obrigado,</p>
                        <p>{app_name}</p>',
                ],
            ],
            'New Room Booking Invoice' => [
                'subject' => 'Room Booking Invoice Create',
                'variables' => '{
                    "Room Booking Invoice Number": "invoice_id",
                    "Room Booking Invoice Customer": "invoice_customer",
                    "Room Booking Invoice Payment Status": "invoice_payment_status",
                    "Room Booking Invoice Total": "invoice_sub_total",
                    "Room Booking Invoice Issue Date": "created_at",
                    "App Url": "app_url",
                    "App Name": "app_name"
                  }',
                'lang' => [
                    'ar' => 'العزيز<span style="font-size: 12pt;">&nbsp;{invoice_customer}</span><span style="font-size: 12pt;">,</span><br><br>لقد قمنا بإعداد الفاتورة التالية من أجلك<span style="font-size: 12pt;">: </span><strong style="font-size: 12pt;">&nbsp;{invoice_id}</strong><br><br>حالة الفاتورة<span style="font-size: 12pt;">: {invoice_payment_status}</span><br><br><br>يرجى الاتصال بنا للحصول على مزيد من المعلومات<span style="font-size: 12pt;">.</span><br><br>أطيب التحيات<span style="font-size: 12pt;">,</span><br>{app_name}',
                    'da' => 'Kære<span style="font-size: 12pt;">&nbsp;{invoice_customer}</span><span style="font-size: 12pt;">,</span><br><br>Vi har udarbejdet følgende faktura til dig<span style="font-size: 12pt;">:&nbsp;&nbsp;{invoice_id}</span><br><br>Fakturastatus: {invoice_payment_status}<br><br>Kontakt os for mere information<span style="font-size: 12pt;">.</span><br><br>Med venlig hilsen<span style="font-size: 12pt;">,</span><br>{app_name}',
                    'de' => '<p><b>sehr geehrter</b><span style="font-size: 12pt;">&nbsp;{invoice_customer}</span><br><br>Wir haben die folgende Rechnung für Sie vorbereitet<span style="font-size: 12pt;">: {invoice_id}</span><br><br><b>Rechnungsstatus</b><span style="font-size: 12pt;">: {invoice_payment_status}</span></p><p>Bitte kontaktieren Sie uns für weitere Informationen<span style="font-size: 12pt;">.</span><br><br><b>Mit freundlichen Grüßen</b><span style="font-size: 12pt;">,</span><br>{app_name}</p>',
                    'en' => '<p><span style="font-size: 12pt;"><strong>Dear</strong>&nbsp;{invoice_customer}</span><span style="font-size: 12pt;">,</span></p>
                            <p><span style="font-size: 12pt;">We have prepared the following invoice for you :#{invoice_id}</span></p>
                            <p><span style="font-size: 12pt;"><strong>Invoice Status</strong> : {invoice_payment_status}</span></p>
                            <p>Please Contact us for more information.</p>
                            <p><span style="font-size: 12pt;">&nbsp;</span></p>
                            <p><strong>Kind Regards</strong>,<br /><span style="font-size: 12pt;">{app_name}</span></p>',
                    'es' => '<p><b>Querida</b><span style="font-size: 12pt;">&nbsp;{invoice_customer}</span><span style="font-size: 12pt;">,</span></p><p>Hemos preparado la siguiente factura para ti<span style="font-size: 12pt;">:&nbsp;&nbsp;{invoice_id}</span></p><p><b>Estado de la factura</b><span style="font-size: 12pt;">: {invoice_payment_status}</span></p><p>Por favor contáctenos para más información<span style="font-size: 12pt;">.</span></p><p><b>Saludos cordiales</b><span style="font-size: 12pt;">,<br></span>{app_name}</p>',
                    'fr' => '<p><b>Cher</b><span style="font-size: 12pt;">&nbsp;{invoice_customer}</span><span style="font-size: 12pt;">,</span></p><p>Nous avons préparé la facture suivante pour vous<span style="font-size: 12pt;">: {invoice_id}</span></p><p><b>État de la facture</b><span style="font-size: 12pt;">: {invoice_payment_status}</span></p><p>Veuillez nous contacter pour plus d\'informations<span style="font-size: 12pt;">.</span></p><p><b>Sincères amitiés</b><span style="font-size: 12pt;">,<br></span>{app_name}</p>',
                    'it' => '<p><b>Caro</b><span style="font-size: 12pt;">&nbsp;{invoice_customer}</span><span style="font-size: 12pt;">,</span></p><p>Abbiamo preparato per te la seguente fattura<span style="font-size: 12pt;">:&nbsp;&nbsp;{invoice_id}</span></p><p><b>Stato della fattura</b><span style="font-size: 12pt;">: {invoice_payment_status}</span></p><p>Vi preghiamo di contattarci per ulteriori informazioni<span style="font-size: 12pt;">.</span></p><p><b>Cordiali saluti</b><span style="font-size: 12pt;">,<br></span>{app_name}</p>',
                    'ja' => '親愛な<span style="font-size: 12pt;">&nbsp;{invoice_customer}</span><span style="font-size: 12pt;">,</span><br><br>以下の請求書をご用意しております。<span style="font-size: 12pt;">: {invoice_customer}</span><br><br>請求書のステータス<span style="font-size: 12pt;">: {invoice_payment_status}</span><br><br>詳しくはお問い合わせください<span style="font-size: 12pt;">.</span><br><br>敬具<span style="font-size: 12pt;">,</span><br>{app_name}',
                    'nl' => '<p><b>Lieve</b><span style="font-size: 12pt;">&nbsp;{invoice_customer}</span><span style="font-size: 12pt;">,</span></p><p>We hebben de volgende factuur voor u opgesteld<span style="font-size: 12pt;">: {invoice_id}</span></p><p><b>Factuurstatus</b><span style="font-size: 12pt;">: {invoice_payment_status}</span></p><p>Voor meer informatie kunt u contact met ons opnemen<span style="font-size: 12pt;">.</span></p><p><b>Vriendelijke groeten</b><span style="font-size: 12pt;">,<br></span>{app_name}</p>',
                    'pl' => '<p><b>Drogi</b><span style="font-size: 12pt;">&nbsp;{invoice_customer}</span><span style="font-size: 12pt;">,</span></p><p>Przygotowaliśmy dla Ciebie następującą fakturę<span style="font-size: 12pt;">: {invoice_id}</span></p><p><b>Status faktury</b><span style="font-size: 12pt;">: {invoice_payment_status}</span></p><p>Skontaktuj się z nami, aby uzyskać więcej informacji<span style="font-size: 12pt;">.</span></p><p><b>Z poważaniem</b><span style="font-size: 12pt;"><b>,</b><br></span>{app_name}</p>',
                    'ru' => '<p><b>дорогая</b><span style="font-size: 12pt;">&nbsp;{invoice_customer}</span><span style="font-size: 12pt;">,</span></p><p>Мы подготовили для вас следующий счет<span style="font-size: 12pt;">: {invoice_id}</span></p><p><b>Статус счета</b><span style="font-size: 12pt;">: {invoice_payment_status}</span></p><p>Пожалуйста, свяжитесь с нами для получения дополнительной информации<span style="font-size: 12pt;">.</span></p><p><b>С уважением</b><span style="font-size: 12pt;">,<br></span>{app_name}</p>',
                    'pt' => '<p><b>Querida</b><span style="font-size: 12pt;">&nbsp;{invoice_customer}</span><span style="font-size: 12pt;">,</span></p><p>Preparamos a seguinte fatura para você<span style="font-size: 12pt;">: {invoice_id}</span></p><p><b>Status da fatura</b><span style="font-size: 12pt;">: {invoice_payment_status}</span></p><p>Entre em contato conosco para mais informações.<span style="font-size: 12pt;">.</span></p><p><b>Atenciosamente</b><span style="font-size: 12pt;">,<br></span>{app_name}</p>',
                ],
            ],
            'New Room Booking Invoice Payment' => [
                'subject' => 'Room Booking Invoice Payment Send',
                'variables' => '{
                        "App Name": "app_name",
                        "Hotel Name": "hotel_name",
                        "App Url": "app_url",
                        "Payment Name": "payment_name",
                        "Invoice Number": "invoice_number",
                        "Payment Amount": "payment_amount",
                        "Payment Method": "payment_method",
                        "Payment Date": "payment_date"
                  }',
                  'lang' => [
                        'ar' => '<p>مرحبًا</p>
                        <p>مرحبًا بك في {app_name}</p>
                        <p>عزيزي {Payment_name}</p>
                        <p>لقد استلمنا دفعتك بمبلغ {Payment_amount} مقابل {invoice_number} الذي تم إرساله في التاريخ {Payment_date}.</p>
                        <p>رقم {invoice_number} الخاص بك ونوع غرفتك هو {room_type}.</p>
                        <p>إذا طلبت أي وسائل راحة أو خدمات إضافية، فسنكون سعداء بتقديمها لك أثناء إقامتك.</p>
                        <p>نشكرك مجددًا على اختيارك لنا ونتطلع إلى استضافتك كضيفنا.</p>
                        <p> </p>
                        <p>مع تحياتي</p>
                        <p>{hotel_name}</p>
                        <p>{app_url}</p>',
                        'da' => '<p>Hej</p>
                        <p>Velkommen til {app_name}</p>
                        <p>Kære {payment_name}</p>
                        <p>Vi har modtaget dit beløb {payment_amount} betaling for {invoice_number} indsendt på datoen {payment_date}.</p>
                        <p>Dit {invoice_number} og din værelsestype er {room_type}.</p>
                        <p>Hvis du har anmodet om yderligere faciliteter eller tjenester, vil vi med glæde levere dem til dig under dit ophold.</p>
                        <p>Tak, fordi du valgte os, og vi ser frem til at have dig som vores gæst.</p>
                        <p> </p>
                        <p>Med venlig hilsen</p>
                        <p>{hotel_name}</p>
                        <p>{app_url}</p>',
                        'de' => '<p>Hallo,</p>
                        <p>Willkommen bei {app_name}</p>
                        <p>Sehr geehrter {payment_name}</p>
                        <p>Wir haben Ihre Zahlung in Höhe von {payment_amount} für {invoice_number} erhalten, die am Datum {payment_date} eingereicht wurde.</p>
                        <p>Ihre {invoice_number} und Ihr Zimmertyp sind {room_type}.</p>
                        <p>Wenn Sie zusätzliche Annehmlichkeiten oder Dienstleistungen wünschen, stellen wir Ihnen diese gerne während Ihres Aufenthalts zur Verfügung.</p>
                        <p>Nochmals vielen Dank, dass Sie sich für uns entschieden haben und wir freuen uns, Sie als unseren Gast begrüßen zu dürfen.</p>
                        <p> </p>
                        <p>Grüße</p>
                        <p>{Hotel_name}</p>
                        <p>{app_url}</p>',
                        'en' => '<p><span style="color: #1d1c1d; font-family: Slack-Lato, Slack-Fractions, appleLogo, sans-serif;"><span style="font-size: 15px; font-variant-ligatures: common-ligatures;">Hi,</span></span></p>
                        <p><span style="color: #1d1c1d; font-family: Slack-Lato, Slack-Fractions, appleLogo, sans-serif;"><span style="font-size: 15px; font-variant-ligatures: common-ligatures;">Welcome to {app_name}</span></span></p>
                        <p><span style="color: #1d1c1d; font-family: Slack-Lato, Slack-Fractions, appleLogo, sans-serif;"><span style="font-size: 15px; font-variant-ligatures: common-ligatures;">Dear {payment_name}</span></span></p>
                        <p><span style="color: #1d1c1d; font-family: Slack-Lato, Slack-Fractions, appleLogo, sans-serif;"><span style="font-size: 15px; font-variant-ligatures: common-ligatures;">We have recieved your amount {payment_amount} payment for {invoice_number} submited on date {payment_date}.</span></span></p>
                        <p><span style="color: #1d1c1d; font-family: Slack-Lato, Slack-Fractions, appleLogo, sans-serif;"><span style="font-size: 15px; font-variant-ligatures: common-ligatures;">Your {invoice_number} and your room type is {room_type}.</span></span></p>
                        <p><span style="color: #1d1c1d; font-family: Slack-Lato, Slack-Fractions, appleLogo, sans-serif;"><span style="font-size: 15px; font-variant-ligatures: common-ligatures;">If you requested any additional amenities or services, we will be happy to provide those for you during your stay.</span></span></p>
                        <p><span style="color: #1d1c1d; font-family: Slack-Lato, Slack-Fractions, appleLogo, sans-serif;"><span style="font-size: 15px; font-variant-ligatures: common-ligatures;">Thank you again for choosing us and we look forward to having you as our guest.</span></span></p>
                        <p>&nbsp;</p>
                        <p><span style="color: #1d1c1d; font-family: Slack-Lato, Slack-Fractions, appleLogo, sans-serif;"><span style="font-size: 15px; font-variant-ligatures: common-ligatures;">Regards,</span></span></p>
                        <p><span style="color: #1d1c1d; font-family: Slack-Lato, Slack-Fractions, appleLogo, sans-serif;"><span style="font-size: 15px; font-variant-ligatures: common-ligatures;">{hotel_name}</span></span></p>
                        <p><span style="color: #1d1c1d; font-family: Slack-Lato, Slack-Fractions, appleLogo, sans-serif;"><span style="font-size: 15px; font-variant-ligatures: common-ligatures;">{app_url}</span></span></p>',
                        'es' => '<p>Hola,</p>
                        <p>Bienvenido a {app_name}</p>
                        <p>Estimado {payment_name}</p>
                        <p>Hemos recibido el pago del importe {payment_amount} de {invoice_number} enviado en la fecha {payment_date}.</p>
                        <p>Su {invoice_number} y su tipo de habitación es {tipo_habitación}.</p>
                        <p>Si solicitó alguna amenidad o servicio adicional, estaremos encantados de proporcionárselo durante su estadía.</p>
                        <p>Gracias nuevamente por elegirnos y esperamos tenerlo como nuestro invitado.</p>
                        <p> </p>
                        <p>Saludos,</p>
                        <p>{hotel_name}</p>
                        <p>{app_url}</p>',
                        'fr' => '<p>Bonjour,</p>
                        <p>Bienvenue sur {app_name}</p>
                        <p>Cher {payment_name}</p>
                        <p>Nous avons reçu votre paiement du montant {payment_amount} pour {invoice_number} soumis à la date {payment_date}.</p>
                        <p>Votre {invoice_number} et votre type de chambre est {room_type}.</p>
                        <p>Si vous avez demandé des commodités ou des services supplémentaires, nous serons heureux de vous les fournir pendant votre séjour.</p>
                        <p>Merci encore de nous avoir choisis et nous sommes impatients de vous compter parmi nos invités.</p>
                        <p> </p>
                        <p>Cordialement,</p>
                        <p>{hotel_name}</p>
                        <p>{app_url}</p>',
                        'it' => '<p>Ciao,</p>
                        <p>Benvenuto in {app_name}</p>
                        <p>Gentile {payment_name}</p>
                        <p>Abbiamo ricevuto il pagamento dell importo di {payment_amount} per {invoice_number} inviato in data {payment_date}.</p>
                        <p>Il tuo {invoice_number} e la tipologia di camera sono {room_type}.</p>
                        <p>Se hai richiesto comfort o servizi aggiuntivi, saremo lieti di fornirteli durante il tuo soggiorno.</p>
                        <p>Grazie ancora per averci scelto e non vediamo l ora di averti come nostro ospite.</p>
                        <p> </p>
                        <p>Saluti,</p>
                        <p>{hotel_name}</p>
                        <p>{app_url}</p>',
                        'ja' => '<p>こんにちは。</p>
                        <p>{app_name} へようこそ</p>
                        <p>{payment_name} 様</p>
                        <p>日付 {payment_date} に送信された、{invoice_number} に対する金額 {payment_amount} のお支払いを受領いたしました。</p>
                        <p>あなたの {invoice_number} と部屋のタイプは {room_type} です。</p>
                        <p>追加のアメニティやサービスをご希望の場合は、ご滞在中に喜んでご提供させていただきます。</p>
                        <p>当ホテルをお選びいただきまして誠にありがとうございます。ゲストとしてお越しいただけることを楽しみにしております。</p>
                        <p> </p>
                        <p>よろしくお願いします。</p>
                        <p>{hotel_name}</p>
                        <p>{app_url}</p>',
                        'nl' => '<p>Hallo,</p>
                        <p>Welkom bij {app_name}</p>
                        <p>Beste {Payment_name}</p>
                        <p>We hebben uw bedrag {payment_amount} ontvangen voor {invoice_number} ingediend op datum {payment_date}.</p>
                        <p>Uw {invoice_number} en uw kamertype zijn {room_type}.</p>
                        <p>Als u extra voorzieningen of diensten heeft aangevraagd, zullen wij deze graag voor u verzorgen tijdens uw verblijf.</p>
                        <p>Nogmaals bedankt dat u voor ons heeft gekozen en we kijken ernaar uit u als onze gast te mogen verwelkomen.</p>
                        <p> </p>
                        <p>Groeten,</p>
                        <p>{hotel_name}</p>
                        <p>{app_url}</p>',
                        'pl' => '<p>Cześć,</p>
                        <p>Witamy w aplikacji {app_name}</p>
                        <p>Szanowni Państwo, {payment_name}</p>
                        <p>Otrzymaliśmy Twoją kwotę {payment_amount} płatności za {invoice_number} przesłaną w dniu {payment_date}.</p>
                        <p>Twój {invoice_number} i typ pokoju to {room_type}.</p>
                        <p>Jeśli zażądałeś dodatkowych udogodnień lub usług, z przyjemnością zapewnimy je podczas Twojego pobytu.</p>
                        <p>Jeszcze raz dziękujemy za wybranie nas i cieszymy się, że będziesz naszym gościem.</p>
                        <p></p>
                        <p>Pozdrowienia,</p>
                        <p>{hotel_name}</p>
                        <p>{app_url}</p>',
                        'ru' => '<p>Привет</p>
                        <p>Добро пожаловать в {app_name}</p>
                        <p>Уважаемый {payment_name}</p>
                        <p>Мы получили ваш платеж на сумму {payment_amount} за {invoice_number}, отправленный на дату {payment_date}.</p>
                        <p>Ваш {invoice_number} и тип номера: {room_type}.</p>
                        <p>Если вы запросили какие-либо дополнительные удобства или услуги, мы будем рады предоставить их вам во время вашего пребывания.</p>
                        <p>Еще раз благодарим вас за выбор нас и будем рады видеть вас в качестве нашего гостя.</p>
                        <p> </p>
                        <p>С уважением,</p>
                        <p>{hotel_name}</p>
                        <p>{app_url}</p>',
                        'pt' => '<p>Olá,</p>
                        <p>Bem-vindo ao {app_name}</p>
                        <p>Prezado {payment_name}</p>
                        <p>Recebemos seu pagamento no valor {payment_amount} referente a {invoice_number} enviado na data {payment_date}.</p>
                        <p>Seu {invoice_number} e seu tipo de quarto são {room_type}.</p>
                        <p>Se você solicitou quaisquer comodidades ou serviços adicionais, teremos prazer em fornecê-los durante sua estadia.</p>
                        <p>Obrigado mais uma vez por nos escolher e esperamos tê-lo como nosso convidado.</p>
                        <p> </p>
                        <p>Atenciosamente,</p>
                        <p>{hotel_name}</p>
                        <p>{app_url}</p>',
                    ],
                ],
            'Room Booking Invoice Status Updated' => [
                'subject' => 'Room Booking Invoice Status Updated',
                'variables' => '{
                        "App Name": "app_name",
                        "App Url": "app_url",
                        "Hotel Customer Name": "hotel_customer_name",
                        "Payment Status": "payment_status",
                        "Room Booking Id": "room_booking_id",
                        "Check In Date": "check_in_date"
                }',
                'lang' => [
                        'ar' => '<p>عزيزي، {hotel_customer_name}</p>
                                <p>لقد تغيرت حالة الدفع لحجز غرفتك في الفندق إلى {Payment_status}</p>
                                <p><strong>معرف حجز الغرفة</strong>: {room_booking_id}<br /><strong>تاريخ تسجيل الوصول</strong>: {check_in_date}<br /><br /><br />مع أطيب التحيات، <br />{app_name}</p>',
                        'da' => '<p>Kære {hotel_customer_name}</p>
                        <p>Din betalingsstatus for booking af hotelværelse er ændret til {payment_status}</p>
                        <p><strong>Værelsesreservations-id</strong>: {room_booking_id}<br /><strong>Indtjekningsdato</strong>: {check_in_date}<br /><br /><br />Med venlig hilsen, <br />{app_name}</p>',
                        'de' => '<p>Lieber {hotel_customer_name}</p>
                                <p>Der Zahlungsstatus Ihrer Hotelzimmerbuchung hat sich in {payment_status}</p> geändert
                                <p><strong>Zimmerbuchungs-ID</strong>: {room_booking_id}<br /><strong>Check-in-Datum</strong>: {check_in_date}<br /><br /><br />Mit freundlichen Grüßen, <br />{app_name}</p>',
                        'en' => '<p>Dear, {hotel_customer_name}</p>
                        <p>Your hotel room booking payment status has changed to {payment_status}</p>
                        <p><strong>Room Booking Id</strong>: {room_booking_id}<br /><strong>Check In Date</strong>: {check_in_date}<br /><br /><br />Kind Regards,<br />{app_name}</p>',
                        'es' => '<p>Querido, {hotel_customer_name}</p>
                        <p>El estado de pago de la reserva de tu habitación de hotel ha cambiado a {payment_status}</p>
                        <p><strong>ID de reserva de habitación</strong>: {room_booking_id}<br /><strong>Fecha de entrada</strong>: {check_in_date}<br /><br /><br />Saludos amables, <br />{app_name}</p>',
                        'fr' => '<p>Cher, {hotel_customer_name}</p>
                        <p>Le statut de paiement de votre réservation de chambre d hôtel est passé à {payment_status}</p>
                        <p><strong>Identifiant de réservation de chambre</strong> : {room_booking_id}<br /><strong>Date d arrivée</strong> : {check_in_date}<br /><br /><br />Cordialement, <br/>{app_name}</p>',
                        'it' => '<p>Caro, {hotel_customer_name}</p>
                        <p>Lo stato del pagamento della prenotazione della camera d albergo è cambiato in {payment_status}</p>
                        <p><strong>ID prenotazione camera</strong>: {room_booking_id}<br /><strong>Data di check-in</strong>: {check_in_date}<br /><br /><br />Cordiali saluti, <br />{app_name}</p>',
                        'ja' => '<p>親愛なる、{hotel_customer_name} 様</p>
                        <p>ホテルの予約のお支払いステータスが {payment_status} に変更されました</p>
                        <p><strong>宿泊予約 ID</strong>: {room_booking_id}<br /><strong>チェックイン日</strong>: {check_in_date}<br /><br /><br />よろしくお願いいたします。 <br />{app_name}</p>',
                        'nl' => '<p>Beste, {hotel_customer_name}</p>
                                <p>De betalingsstatus van uw hotelkamerboeking is gewijzigd in {Payment_status}</p>
                                <p><strong>Kamerboekings-ID</strong>: {room_booking_id}<br /><strong>Incheckdatum</strong>: {check_in_date}<br /><br /><br />Vriendelijke groeten, <br />{app_name}</p>',
                        'pl' => '<p>Szanowni Państwo, {hotel_customer_name}</p>
                                <p>Stan płatności za rezerwację pokoju hotelowego zmienił się na {payment_status}</p>
                                <p><strong>Identyfikator rezerwacji pokoju</strong>: {room_booking_id}<br /><strong>Data zameldowania</strong>: {check_in_date}><br /><br /><br />Z pozdrowieniami, <br />{app_name}</p>',
                        'ru' => '<p>Уважаемый, {hotel_customer_name}</p>
                                <p>Статус оплаты вашего бронирования номера в отеле изменился на {payment_status}</p>
                                <p><strong>Идентификатор бронирования номера</strong>: {room_booking_id><br /><strong>Дата заезда</strong>: {check_in_date}<br /><br /><br />С уважением, <br />{app_name}</p>',
                        'pt' => '<p>Prezado, {hotel_customer_name}</p>
                        <p>O status de pagamento da sua reserva de quarto de hotel mudou para {payment_status}</p>
                        <p><strong>ID da reserva do quarto</strong>: {room_booking_id}<br /><strong>Data de check-in</strong>: {check_in_date}<br /><br /><br />Atenciosamente, <br />{app_name}</p>',
                ],
            ],
        'New Room Booking By Hotel Customer' => [
            'subject' => 'Room Booking By Hotel Customer',
            'variables' => '{
                    "App Name": "app_name",
                    "Hotel Name": "hotel_name",
                    "App Url": "app_url",
                    "Hotel Customer Name": "hotel_customer_name",
                    "Invoice Number": "invoice_number",
                    "Check In Date": "check_in_date",
                    "Check Out Date": "check_out_date",
                    "Room Type": "room_type"
                }',
                'lang' => [
                    'ar' => '<p>عزيزي فريق {hotel_name}</p>
                    <p>اسمي {hotel_customer_name}. لقد حجزنا غرفة في فندقك {hotel_name}.</p>
                    <p>معرف فاتورة حجز الغرفة: {invoice_number}</p>
                    <p>تاريخ تسجيل الوصول: {check_in_date}</p>
                    <p>تاريخ الخروج: {check_out_date}</p>
                    <p>نوع الغرفة: {room_type}</p>
                    <p>بالإضافة إلى ذلك، إذا كان هناك أي ترقيات أو وسائل راحة مجانية يمكن توفيرها، مثل [خدمة أو وسائل راحة محددة]، فسيؤدي ذلك إلى رفع مستوى إقامتنا حقًا. ونحن على استعداد للنظر في أي رسوم إضافية مرتبطة بهذه التحسينات.</p>
                    <p> </p>
                    <p>مع تحياتي</p>
                    <p>{hotel_customer_name}</p>
                    <p>{invoice_number}</p>',
                    'da' => '<p>Kære {hotel_name}-team</p>
                    <p>Mit navn er {hotel_customer_name}. Vi har reserveret værelse på dit hotel {hotel_name}.</p>
                    <p>Faktura-id for værelsesbestilling: {invoice_number}</p>
                    <p>Indtjekningsdato: {check_in_date}</p>
                    <p>Tjek ud dato: {check_out_date}</p>
                    <p>Rumstype: {room_type}</p>
                    <p>Derudover, hvis der er nogen gratis opgraderinger eller faciliteter, der kunne leveres, såsom [specifik service eller faciliteter], ville det virkelig løfte vores ophold. Vi er villige til at overveje eventuelle ekstra omkostninger forbundet med disse forbedringer.</p>
                    <p> </p>
                    <p>Med venlig hilsen</p>
                    <p>{hotel_customer_name}</p>
                    <p>{invoice_number}</p>',
                    'de' => '<p>Liebes {hotel_name}-Team</p>
                    <p>Mein Name ist {hotel_customer_name}. Wir haben ein Zimmer in Ihrem Hotel {hotel_name} gebucht.</p>
                    <p>Rechnungs-ID für die Zimmerbuchung: {invoice_number}</p>
                    <p>Check-in-Datum: {check_in_date}</p>
                    <p>Auscheckdatum: {check_out_date}</p>
                    <p>Raumtyp: {room_type}</p>
                    <p>Wenn darüber hinaus kostenlose Upgrades oder Annehmlichkeiten angeboten werden könnten, wie z. B. [bestimmter Service oder Annehmlichkeiten], würde dies unseren Aufenthalt wirklich verbessern. Wir sind bereit, alle mit diesen Verbesserungen verbundenen zusätzlichen Kosten zu berücksichtigen.</p>
                    <p> </p>
                    <p>Grüße</p>
                    <p>{hotel_customer_name}</p>
                    <p>{invoice_number}</p>',
                    'en' => '<p><span style="color: #1d1c1d; font-family: Slack-Lato, Slack-Fractions, appleLogo, sans-serif;"><span style="font-size: 15px; font-variant-ligatures: common-ligatures;">Dear {hotel_name} Team</span></span></p>
                    <p><span style="color: #1d1c1d; font-family: Slack-Lato, Slack-Fractions, appleLogo, sans-serif;"><span style="font-size: 15px; font-variant-ligatures: common-ligatures;">My name is {hotel_customer_name}. We have booked room at your hotel {hotel_name}.</span></span></p>
                    <p><span style="color: #1d1c1d; font-family: Slack-Lato, Slack-Fractions, appleLogo, sans-serif;"><span style="font-size: 15px; font-variant-ligatures: common-ligatures;">Room Booking Invoice Id : {invoice_number}</span></span></p>
                    <p><span style="color: #1d1c1d; font-family: Slack-Lato, Slack-Fractions, appleLogo, sans-serif;"><span style="font-size: 15px; font-variant-ligatures: common-ligatures;">Check in date : {check_in_date}</span></span></p>
                    <p><span style="color: #1d1c1d; font-family: Slack-Lato, Slack-Fractions, appleLogo, sans-serif;"><span style="font-size: 15px; font-variant-ligatures: common-ligatures;">Check out date : {check_out_date}</span></span></p>
                    <p><span style="color: #1d1c1d; font-family: Slack-Lato, Slack-Fractions, appleLogo, sans-serif;"><span style="font-size: 15px; font-variant-ligatures: common-ligatures;">Room type : {room_type}</span></span></p>
                    <p><span style="color: #1d1c1d; font-family: Slack-Lato, Slack-Fractions, appleLogo, sans-serif;"><span style="font-size: 15px; font-variant-ligatures: common-ligatures;">Additionally, if there are any complimentary upgrades or amenities that could be provided, such as [specific service or amenity], it would truly elevate our stay. We are willing to consider any additional charges associated with these enhancements.</span></span></p>
                    <p>&nbsp;</p>
                    <p><span style="color: #1d1c1d; font-family: Slack-Lato, Slack-Fractions, appleLogo, sans-serif;"><span style="font-size: 15px; font-variant-ligatures: common-ligatures;">Regards,</span></span></p>
                    <p><span style="color: #1d1c1d; font-family: Slack-Lato, Slack-Fractions, appleLogo, sans-serif;"><span style="font-size: 15px; font-variant-ligatures: common-ligatures;">{hotel_customer_name}</span></span></p>
                    <p><span style="color: #1d1c1d; font-family: Slack-Lato, Slack-Fractions, appleLogo, sans-serif;"><span style="font-size: 15px; font-variant-ligatures: common-ligatures;">{invoice_number}</span></span></p>',
                    'es' => '<p>Estimado equipo de {hotel_name}</p>
                    <p>Mi nombre es {hotel_customer_name}. Hemos reservado habitación en tu hotel {hotel_name}.</p>
                    <p>Id. de factura de reserva de habitación: {invoice_number}</p>
                    <p>Fecha de entrada: {check_in_date}</p>
                    <p>Fecha de salida: {check_out_date}</p>
                    <p>Tipo de habitación: {room_type}</p>
                    <p>Además, si se pudieran brindar mejoras o servicios complementarios, como [servicio o amenidad específica], realmente mejoraría nuestra estadía. Estamos dispuestos a considerar cualquier cargo adicional asociado con estas mejoras.</p>
                    <p> </p>
                    <p>Saludos,</p>
                    <p>{hotel_customer_name}</p>
                    <p>{invoice_number}</p>',
                    'fr' => '<p>Chère équipe {hotel_name}</p>
                    <p>Je m appelle {hotel_customer_name}. Nous avons réservé une chambre dans votre hôtel {hotel_name}.</p>
                    <p>Identifiant de la facture de réservation de chambre : {invoice_number}></p>
                    <p>Date d arrivée : {check_in_date}</p>
                    <p>Date de départ : {check_out_date}</p>
                    <p>Type de chambre : {room_type}</p>
                    <p>De plus, si des surclassements ou des équipements gratuits pouvaient être fournis, tels que [un service ou un équipement spécifique], cela rehausserait véritablement notre séjour. Nous sommes prêts à prendre en compte tous les frais supplémentaires associés à ces améliorations.</p>
                    <p> </p>
                    <p>Cordialement,</p>
                    <p>{hotel_customer_name}</p>
                    <p>{invoice_number}</p>',
                    'it' => '<p>Caro team di {hotel_name}</p>
                    <p>Mi chiamo {hotel_customer_name}. Abbiamo prenotato una camera nel tuo hotel {hotel_name}.</p>
                    <p>ID fattura prenotazione camera: {invoice_number}</p>
                    <p>Data di check-in: {check_in_date}</p>
                    <p>Data di partenza: {check_out_date}</p>
                    <p>Tipo di camera: {room_type}</p>
                    <p>Inoltre, se ci fossero aggiornamenti o servizi gratuiti che potrebbero essere forniti, come [servizio o comodità specifici], migliorerebbe davvero il nostro soggiorno. Siamo disposti a prendere in considerazione eventuali costi aggiuntivi associati a questi miglioramenti.</p>
                    <p> </p>
                    <p>Saluti,</p>
                    <p>{hotel_customer_name}</p>
                    <p>{invoice_number}</p>',
                    'ja' => '<p>{hotel_name} チームの皆様</p>
                    <p>私の名前は {hotel_customer_name} です。あなたのホテル {hotel_name} の部屋を予約しました。</p>
                    <p>宿泊予約の請求書 ID : {invoice_number}</p>
                    <p>チェックイン日 : {check_in_date}</p>
                    <p>チェックアウト日 : {check_out_date}</p>
                    <p>部屋のタイプ : {room_type}</p>
                    <p>さらに、[特定のサービスやアメニティ] など、無料のアップグレードやアメニティが提供されると、滞在が本当に向上するでしょう。これらの機能強化に関連する追加料金については、喜んで検討させていただきます。</p>
                    <p> </p>
                    <p>よろしくお願いします。</p>
                    <p>{hotel_customer_name}</p>
                    <p>{invoice_number}</p>',
                    'nl' => '<p>Beste {hotel_name}-team</p>
                    <p>Mijn naam is {hotel_customer_name}. We hebben een kamer geboekt in uw hotel {hotel_name}.</p>
                    <p>Factuur-ID kamerboeking: {invoice_number}</p>
                    <p>Incheckdatum: {check_in_date}</p>
                    <p>Uitcheckdatum: {check_out_date}</p>
                    <p>Kamertype: {room_type}</p>
                    <p>Als er bovendien gratis upgrades of voorzieningen beschikbaar zijn, zoals [specifieke service of voorziening], zou dit ons verblijf echt naar een hoger niveau tillen. We zijn bereid eventuele extra kosten in verband met deze verbeteringen in overweging te nemen.</p>
                    <p> </p>
                    <p>Groeten,</p>
                    <p>{hotel_customer_name}</p>
                    <p>{invoice_number}</p>',
                    'pl' => '<p>Szanowny zespół {hotel_name}</p>
                    <p>Nazywam się {hotel_customer_name}. Zarezerwowaliśmy pokój w Twoim hotelu {hotel_name}.</p>
                    <p>Identyfikator faktury za rezerwację pokoju: {invoice_number}</p>
                    <p>Data zameldowania: {check_in_date}</p>
                    <p>Data wymeldowania: {check_out_date}</p>
                    <p>Typ pokoju: {room_type}</p>
                    <p>Dodatkowo, jeśli można zapewnić bezpłatne ulepszenia lub udogodnienia, takie jak [konkretna usługa lub udogodnienie], naprawdę podniosłoby to poziom naszego pobytu. Jesteśmy gotowi rozważyć wszelkie dodatkowe opłaty związane z tymi ulepszeniami.</p>
                    <p></p>
                    <p>Pozdrowienia,</p>
                    <p>{hotel_customer_name}</p>
                    <p>{invoice_number}</p>',
                    'ru' => '<p>Уважаемая команда {hotel_name}!</p>
                    <p>Меня зовут {hotel_customer_name}. Мы забронировали номер в вашем отеле {hotel_name}.</p>
                    <p>Идентификатор счета за бронирование номера: {invoice_number}</p>
                    <p>Дата заезда: {check_in_date}</p>
                    <p>Дата выезда: {check_out_date}</p>
                    <p>Тип номера: {room_type}</p>
                    <p>Кроме того, если можно предоставить какие-либо бесплатные повышения класса обслуживания или удобства, например [конкретную услугу или удобства], это действительно улучшит наше пребывание. Мы готовы рассмотреть любые дополнительные расходы, связанные с этими улучшениями.</p>
                    <p> </p>
                    <p>С уважением,</p>
                    <p>{hotel_customer_name}</p>
                    <p>{invoice_number}</p>',
                    'pt' => '<p>Prezada equipe do {hotel_name}</p>
                    <p>Meu nome é {hotel_customer_name}. Reservamos um quarto no seu hotel {hotel_name}.</p>
                    <p>Id da fatura da reserva de quarto: {invoice_number}</p>
                    <p>Data de check-in: {check_in_date}</p>
                    <p>Data de check-out: {check_out_date}</p>
                    <p>Tipo de quarto: {room_type}</p>
                    <p>Além disso, se houver quaisquer upgrades ou comodidades gratuitas que possam ser fornecidas, como [serviço ou comodidade específica], isso realmente elevará nossa estadia. Estamos dispostos a considerar quaisquer cobranças adicionais associadas a essas melhorias.</p>
                    <p> </p>
                    <p>Atenciosamente,</p>
                    <p>{hotel_customer_name}</p>
                    <p>{invoice_number}</p>',
                ],
            ],
        ];

        foreach($emailTemplate as $eTemp)
        {
            $table = EmailTemplate::where('name',$eTemp)->where('module_name','Holidayz')->exists();
            if(!$table)
            {
                $emailtemplate=  EmailTemplate::create(
                    [
                        'name' => $eTemp,
                        'from' => 'Holidayz',
                        'module_name' => 'Holidayz',
                        'created_by' => 1,
                        'workspace_id' => 0
                    ]
                );
                foreach($defaultTemplate[$eTemp]['lang'] as $lang => $content)
                {
                    EmailTemplateLang::create(
                        [
                            'parent_id' => $emailtemplate->id,
                            'lang' => $lang,
                            'subject' => $defaultTemplate[$eTemp]['subject'],
                            'variables' => $defaultTemplate[$eTemp]['variables'],
                            'content' => $content,
                        ]
                    );
                }
            }
        }

    }
}
