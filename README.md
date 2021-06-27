# Spordiruumide broneerimissüsteem

Tere tulemast minu lõputöö projekti GitHubi repositooriumisse!

Olen Annemarii Hunt ning olen Tallinna Ülikooli Haapsalu Kolledži eriala rakendusinformaatika vilistlane. Kirjutasin 2020a lõputööd teemal "Spordiruumide veebikalendri prototüübi edasiarendus Pärnu Linnavalitsuse näitel" ning samal ajal ehitasin rakendust.

Rakenduse näol on tegemist kohaliku omavalitsuse spordiga seotud allasutuste spordiruumide kalendrisüsteemi rakendusega. Rakendus on edasi arendatud valikpraktika projektist https://github.com/Viiskorda/maag.

Demo keskkonnaga saad tutvuda lingil https://www.spordiruumid.ee/.

Käesolev töö on GNU v3 litsentsi all ning kõigile huvilistele vabalt kättesaadav. Rakendus kasutab FullCalendar Scheduler moodulit erilitsentsi all, seega igasugune rakenduse funktsionaalsuse lisamine või muutmine peab olema lähtekoodina avalik. Täpsem info https://fullcalendar.io/license/premium. Isiklikul eesmärgil koodi muutmisel ja rakenduse kasutamisel sa ei pea lähtekoodi avaldama.  Täpsemalt loe litsentsitingimusi litsentsifailist https://github.com/Viiskorda/spordiruumid/blob/master/LICENSE.

Oma serveris rakenduse tööle saamiseks on vaja muuta application/config kaustas kaks faili: config.php ning database.php:
```
config.php //tuleb määrata URL $config['base_url']
database.php //tuleb määrata 'hostname', 'username', ning 'password'.
```
Tootmiskeskkonda üles laadimiseks soovitav on sisse lülitada turvalist sessiooni config.php failist:
```
$config['cookie_secure']	= TRUE;
```
Juurkaustas index.php failis seadista toomtmiskeskkond:
```
define('ENVIRONMENT', isset($_SERVER['CI_ENV']) ? $_SERVER['CI_ENV'] : 'development');
```
Lisaks soovitan sisse lülitada PHP veateadete kirjutamist logifaili. Veendu, et kataloog /logs oleks süsteemi poolt kirjutatav. Logimise kirjutamiseks seadista application/config/config.php failis:
```
$config['log_threshold'] = 0;
```
Kui sa ei soovi võtta kasutusele reCAPTCHA v2, siis selle välja lülitamiseks kommenteeri välja User.php kontrollerist järgmine rida:
```
$this->form_validation->set_rules('g-recaptcha-response','Captcha','callback_recaptcha');
```
reCAPTCHA toimimiseks vaja muuta kahte rida kahes failis. Võta lahti kontroller Users.php ning sisesta oma salajane võti järgmisele reale
```
  $secret='selle_asemele_kleebi_salajane_võti';
```
Seejärel võta lahti vaade login.php ning sisesta oma avalik võti
```
<div class="g-recaptcha" data-sitekey="selle_asemele_kleebi_avalik_võti"></div>
```
Täpsemad juhised reCaptcha seadistamise kohta leiad [siit](http://avenir.ro/integrating-googles-recaptcha-in-codeigniters-form-validation-the-callback-way/). reCAPTCHA registreerimiseks mine aadressile https://www.google.com/recaptcha/intro/index.html.

Kui soovid võtta kasutusele sisse logimine Google kontoga kasutades oAuth v2, siis Google arendajate konsoolis tuleb määrata Redirect URL "https://sinu_domeen/login/login", genereerida API võtmed, seejärel tuleb minna kontrollerisse nimega Login.php ning järgmistesse ridadesse panema oma Google poolt genereeritud OAuth 2.0 genereeritud id ja salajane võti.

```
$google_client->setClientId('selle_asemele_kleebi_clientID'); //Kirjuta oma ClientID	 
$google_client->setClientSecret('selle_asemele_kleebi_salajane_võti'); //Kirjuta oma Client Secret Key
$google_client->setRedirectUri('http://localhost/spordiruumid/login/login'); //Vajadusel muuda suunamise universaalset ressursiidentifikaatorit (Redirect Uri), näiteks localhost asemel kirjuta domeeninimi.
```
Facebooki oAuth v2 kasutamiseks registreeri API ning "Facebook For Developers" konsoolist leia registreeritud rakenduse seadetes "OAuth Redirect URIs" ning kirjuta sinna "sinu_domeen/login/fblogin". Seejärel kui oled API võtmed genereerinud tuleb need sisestada config kaustas facebook.php nimelise faili 
```
$config['facebook_app_id']                = 'facebook_app_id'; //asenda oma võtmega
$config['facebook_app_secret']            = 'facebook_app_secret'; //asenda oma võtmega
$config['facebook_login_redirect_url']    = 'login/fblogin'; // Facebook konsoolis seadete all "Valid OAuth Redirect URIs"
$config['facebook_logout_redirect_url']   = 'login/logout';
```
Täpsemad juhised oAuth v2 seadistamiseks leiad [siit](https://www.youtube.com/watch?v=1xCt3cBQ8bQ "Facebooki kohta") ja [siit](https://www.webslesson.info/2020/03/google-login-integration-in-codeigniter.html "Google kohta").


Rakenduse tööle saamiseks tuleb luua MySQL tabelid:
```sql
CREATE TABLE `bookingFormSettings` (
  `formID` int(11) NOT NULL AUTO_INCREMENT,
  `buildingID` int(11) NOT NULL,
  `approved_admin` tinyint(1) NOT NULL DEFAULT 1,
  `clubname_admin` tinyint(1) NOT NULL DEFAULT 1,
  `contactname_admin` tinyint(1) NOT NULL DEFAULT 1,
  `phone_admin` tinyint(1) NOT NULL DEFAULT 0,
  `email_admin` tinyint(1) NOT NULL DEFAULT 0,
  `type_admin` tinyint(1) NOT NULL DEFAULT 0,
  `color1` char(7) NOT NULL DEFAULT '#ffffff',
  `color2` char(7) NOT NULL DEFAULT '#ddffee',
  `color3` char(7) NOT NULL DEFAULT '#cceeff',
  `color4` char(7) NOT NULL DEFAULT '#ffccee',
  `color5` char(7) NOT NULL DEFAULT '#ffffcc',
  `color6` char(7) NOT NULL DEFAULT '#aaffaa',
  `color7` char(7) NOT NULL DEFAULT '#eeffff',
  `color8` char(7) NOT NULL DEFAULT '#f6e5ff',
  `allow_booking` tinyint(1) NOT NULL DEFAULT 0,
  `clubname_user` tinyint(1) NOT NULL DEFAULT 1,
  `contactname_user` tinyint(1) NOT NULL DEFAULT 1,
  `phone_user` tinyint(1) NOT NULL DEFAULT 1,
  `email_user` tinyint(1) NOT NULL DEFAULT 1,
  `type_user` tinyint(1) NOT NULL DEFAULT 1,
	PRIMARY KEY (formID)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `bookingFormSettingsDetails` (
  `onceID` int(11) NOT NULL AUTO_INCREMENT,
  `buildingID` int(11) NOT NULL,
  `typeID` int(11) NOT NULL,
  `showtopublicuser` tinyint(1) NOT NULL DEFAULT 1,
  `maxpeaplenumbersee` tinyint(1) NOT NULL DEFAULT 1,
  `maxpeaplenumberrequired` tinyint(1) NOT NULL DEFAULT 0,
  `groupsee` tinyint(1) NOT NULL DEFAULT 1,
  `grouprequired` tinyint(1) NOT NULL DEFAULT 0,
  `publicsee` tinyint(1) NOT NULL DEFAULT 1,
  `publicrequired` tinyint(1) NOT NULL DEFAULT 0,
  `prepsee` tinyint(1) NOT NULL DEFAULT 1,
  `preprequired` tinyint(1) NOT NULL DEFAULT 0,
  `cleansee` tinyint(1) NOT NULL DEFAULT 1,
  `cleanrequired` tinyint(1) NOT NULL DEFAULT 0,
  `agreementsee` tinyint(1) NOT NULL DEFAULT 1,
  `agreementrequired` tinyint(1) NOT NULL DEFAULT 0,
  `agreementnamesee` tinyint(1) NOT NULL DEFAULT 1,
  `agreementnamerequired` tinyint(1) NOT NULL DEFAULT 0,
  `agreementcodesee` tinyint(1) NOT NULL DEFAULT 1,
  `agreementcoderequired` tinyint(1) NOT NULL DEFAULT 0,
  `agreementaddresssee` tinyint(1) NOT NULL DEFAULT 1,
  `agreementaddressrequired` tinyint(1) NOT NULL DEFAULT 0,
  `agreementcontactsee` tinyint(1) NOT NULL DEFAULT 1,
  `agreementcontactrequired` tinyint(1) NOT NULL DEFAULT 0,
  `agreementemailsee` tinyint(1) NOT NULL DEFAULT 1,
  `agreementemailrequired` tinyint(1) NOT NULL DEFAULT 0,
  `agreementphonesee` tinyint(1) NOT NULL DEFAULT 1,
  `agreementphonerequired` tinyint(1) NOT NULL DEFAULT 0,
  `methodofpaymentsee` tinyint(1) NOT NULL DEFAULT 1,
  `methodofpaymentrequired` tinyint(1) NOT NULL DEFAULT 0,
  `methodofpaymentcash` tinyint(1) NOT NULL DEFAULT 1,
  `methodofpaymentcard` tinyint(1) NOT NULL DEFAULT 1,
  `methodofpaymentbill` tinyint(1) NOT NULL DEFAULT 1,
  `methodofpaymentprepayment` tinyint(1) NOT NULL DEFAULT 1,
  `methodofpaymentother` tinyint(1) NOT NULL DEFAULT 1,
  `invoicesee` tinyint(1) NOT NULL DEFAULT 1,
  `invoicerequired` tinyint(1) NOT NULL DEFAULT 0,
  `invoicenamesee` tinyint(1) NOT NULL DEFAULT 1,
  `invoicenamerequired` tinyint(1) NOT NULL DEFAULT 0,
  `invoicecodesee` tinyint(1) NOT NULL DEFAULT 1,
  `invoicecoderequired` tinyint(1) NOT NULL DEFAULT 0,
  `invoiceaddresssee` tinyint(1) NOT NULL DEFAULT 1,
  `invoiceaddressrequired` tinyint(1) NOT NULL DEFAULT 0,
  `invoicecontact` tinyint(1) NOT NULL DEFAULT 1,
  `invoicecontactrequired` tinyint(1) NOT NULL DEFAULT 0,
  `invoiceemailsee` tinyint(1) NOT NULL DEFAULT 1,
  `invoiceemailrequired` tinyint(1) NOT NULL DEFAULT 0,
  `invoicephonesee` tinyint(1) NOT NULL DEFAULT 1,
  `invoicephonerequired` tinyint(1) NOT NULL DEFAULT 0,
  `intro` text NOT NULL DEFAULT '',
  `emailtext` text NOT NULL DEFAULT '',
	PRIMARY KEY (onceID)
);

CREATE TABLE `bookings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `typeID` int(11) DEFAULT NULL,
  `public_info` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `c_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `c_phone` varchar(12) DEFAULT NULL,
  `c_email` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `comment` text CHARACTER SET utf8 COLLATE utf8_bin,
  `comment_inner` text CHARACTER SET utf8 COLLATE utf8_bin,
  `organizer` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `workout` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `event_in` datetime DEFAULT NULL,
  `event_out` datetime DEFAULT NULL,
	`private` tinyint(1) NOT NULL DEFAULT 1,
  `maximumparticipants` int(11) DEFAULT NULL,
  `target` int(11) DEFAULT 0,
  `agreementID` int(11) DEFAULT NULL,
  `paymentID` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
   PRIMARY KEY (id)
);

CREATE TABLE `users` (
  `userID` int(11) NOT NULL AUTO_INCREMENT,
  `login_oauth_uid` varchar(100) CHARACTER SET utf8 NOT NULL,
  `roleID` int(11) NOT NULL DEFAULT '4',
  `buildingID` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `userName` varchar(255) DEFAULT NULL,
  `userPhone` varchar(255) DEFAULT NULL,
  `pw_hash` varchar(255) NOT NULL,
  `session_id` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL,
	`last_login` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `requestFromBuilding` tinyint default '0',
   PRIMARY KEY (userID)
 );

INSERT INTO `users` (`userID`, `login_oauth_uid`, `roleID`, `buildingID`, `email`, `status`, `userName`, `userPhone`, `pw_hash`, `session_id`, `created_at`, `updated_at`) VALUES
(1, '', 1, 0, 'admin@admin.ee', 1, 'Admin', '12345', '$2y$10$L9ptq3zKFXK447U.m4g48emDTNx2W4C7aQeahRUJsHcuq1sneb/eW', '', '2020-03-02 09:00:46', '0000-00-00 00:00:00');

CREATE TABLE `bookingTypes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
   PRIMARY KEY (id)
);

INSERT INTO `bookingTypes` (`id`, `name`) VALUES
(1, 'trenn'),
(2, 'trenn (hooajaline)'),
(3, 'üritus'),
(4, 'suletud');

CREATE TABLE `bookingTimes` (
  `timeID` int(11) NOT NULL AUTO_INCREMENT,
  `bookingID` int(11) NOT NULL,
  `roomID` int(11) NOT NULL,
  `approved` tinyint(1) DEFAULT '0',
  `takes_place` tinyint(1) DEFAULT '1',
  `startTime` datetime NOT NULL,
  `endTime` datetime NOT NULL,
	`timetoprep` datetime,
  `timetoclean` datetime,
  `bookingTimeColor` char(50) DEFAULT '#ffffff',
  `timecomment` varchar(255) NOT NULL DEFAULT '',
  `showcomment` tinyint(1) NOT NULL DEFAULT 0,
  `hasChanged` tinyint(1) NOT NULL DEFAULT 0,
   PRIMARY KEY (timeID)
);

CREATE TABLE `bookingTimeVersions` (
  `versionID` int(11) NOT NULL AUTO_INCREMENT,
  `timeID` int(11) NOT NULL,
  `startTime` timestamp NULL DEFAULT NULL,
  `endTime` timestamp NULL DEFAULT NULL,
  `nameWhoChanged` varchar(30) DEFAULT NULL,
  `reason` varchar(255) DEFAULT NULL,
  `whenChanged` timestamp NOT NULL DEFAULT current_timestamp(), 
   PRIMARY KEY (versionID)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `buildings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `regionID` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `contact_email` varchar(255) NOT NULL,
  `notify_email` varchar(255) NOT NULL,
  `price_url` varchar(255) NOT NULL,
   PRIMARY KEY (id)
);

CREATE TABLE `regions` (
  `regionID` int(11) NOT NULL AUTO_INCREMENT,
  `regionName` varchar(255) NOT NULL,
   PRIMARY KEY (regionID)
    
);

INSERT INTO `regions` (`regionID`, `regionName`) VALUES
(1, 'Pärnu Kesklinn/Raeküla'),
(2, 'Pärnu Ülejõe'),
(3, 'Audru'),
(4, 'Tõstamaa'),
(5, 'Paikuse');

CREATE TABLE `category` (
  `categoryID` int(11) NOT NULL AUTO_INCREMENT,
  `categoryName` varchar(255) DEFAULT NULL,
	 PRIMARY KEY (categoryID)
);

INSERT INTO `category` (`categoryID`, `categoryName`) VALUES
(1, 'Kõik'),
(2, 'Sport'),
(3, 'Kultuur'),
(4, 'Koolitus'),
(5, 'Avalikud üritused');

CREATE TABLE `rooms` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `buildingID` int(11) NOT NULL,
  `roomName` varchar(255) NOT NULL,
  `roomActive` tinyint(1) NOT NULL DEFAULT '1',
  `roomColor` char(50) DEFAULT '#ffffff',
  `api_url` text DEFAULT '',
   PRIMARY KEY (id)
);

CREATE TABLE `roomcategory` (
  `roomcategoryID` int(11) NOT NULL AUTO_INCREMENT,
  `roomID` int(11) DEFAULT NULL,
  `categoryID` int(11) DEFAULT NULL,
	 PRIMARY KEY (roomcategoryID)
);

CREATE TABLE `userRoles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role` varchar(255) NOT NULL,
   PRIMARY KEY (id)
);

INSERT INTO `userRoles` (`id`, `role`) VALUES
(1, 'Linnavalitsuse administraator'),
(2, 'Peaadministraator'),
(3, 'Administraator'),
(4, 'Tavakasutaja');

CREATE TABLE `agerange` (
  `agerangeID` int(11) NOT NULL AUTO_INCREMENT,
  `nameofrange` varchar(255) DEFAULT NULL,
  PRIMARY KEY (agerangeID)    
);


INSERT INTO `agerange` (`agerangeID`, `nameofrange`) VALUES
(1, 'Segarühm'),
(2, 'Koolinoored (alla 20a)'),
(3, 'Täiskasvanud'),
(4, 'Seeniorid (üle 63a)'),
(5, 'Erivajadustega'),
(6, 'Muu');

CREATE TABLE `paymentandagreement` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `code` varchar(11) NOT NULL DEFAULT '',
  `address` varchar(255) NOT NULL DEFAULT '',
  `contact` varchar(255) NOT NULL DEFAULT '',
  `phone` varchar(20) NOT NULL DEFAULT '',
  `email` varchar(255) NOT NULL DEFAULT '',
	PRIMARY KEY (id)    
);

CREATE TABLE `userrights` (
  `userrightsID` int(11) NOT NULL AUTO_INCREMENT,
  `userID` int(11) NOT NULL,
  `buildingID` int(11) NOT NULL,
  `roleID` int(11) NOT NULL,
  `default_room_id` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (userrightsID)    

) ENGINE=InnoDB DEFAULT CHARSET=latin1;


```

Head rakenduse kasutamist!
