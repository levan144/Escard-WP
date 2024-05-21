<?php
/**
 * Customer new account email
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/plain/customer-new-account.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates\Emails\Plain
 * @version 6.0.0
 */

defined( 'ABSPATH' ) || exit;
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">
  <head>
    <!--[if gte mso 15]>
		<xml>
			<o:OfficeDocumentSettings>
				<o:AllowPNG/>
				<o:PixelsPerInch>96</o:PixelsPerInch>
			</o:OfficeDocumentSettings>
		</xml>
		<![endif]-->
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Welcome to ESCARD</title>
    <style>
      img {
        -ms-interpolation-mode: bicubic;
      }

      table,
      td {
        mso-table-lspace: 0pt;
        mso-table-rspace: 0pt;
      }

      .mceStandardButton,
      .mceStandardButton td,
      .mceStandardButton td a {
        mso-hide: all !important;
      }

      p,
      a,
      li,
      td,
      blockquote {
        mso-line-height-rule: exactly;
      }

      p,
      a,
      li,
      td,
      body,
      table,
      blockquote {
        -ms-text-size-adjust: 100%;
        -webkit-text-size-adjust: 100%;
      }

      @media only screen and (max-width: 480px) {

        body,
        table,
        td,
        p,
        a,
        li,
        blockquote {
          -webkit-text-size-adjust: none !important;
        }
      }

      .mcnPreviewText {
        display: none !important;
      }

      .bodyCell {
        margin: 0 auto;
        padding: 0;
        width: 100%;
      }

      .ExternalClass,
      .ExternalClass p,
      .ExternalClass td,
      .ExternalClass div,
      .ExternalClass span,
      .ExternalClass font {
        line-height: 100%;
      }

      .ReadMsgBody {
        width: 100%;
      }

      .ExternalClass {
        width: 100%;
      }

      a[x-apple-data-detectors] {
        color: inherit !important;
        text-decoration: none !important;
        font-size: inherit !important;
        font-family: inherit !important;
        font-weight: inherit !important;
        line-height: inherit !important;
      }

      body {
        height: 100%;
        margin: 0;
        padding: 0;
        width: 100%;
        background: #ffffff;
      }

      p {
        margin: 0;
        padding: 0;
      }

      table {
        border-collapse: collapse;
      }

      td,
      p,
      a {
        word-break: break-word;
      }

      h1,
      h2,
      h3,
      h4,
      h5,
      h6 {
        display: block;
        margin: 0;
        padding: 0;
      }

      img,
      a img {
        border: 0;
        height: auto;
        outline: none;
        text-decoration: none;
      }

      a[href^="tel"],
      a[href^="sms"] {
        color: inherit;
        cursor: default;
        text-decoration: none;
      }

      li p {
        margin: 0 !important;
      }

      .ProseMirror a {
        pointer-events: none;
      }

      @media only screen and (max-width: 480px) {
        body {
          width: 100% !important;
          min-width: 100% !important;
        }

        body.mobile-native {
          -webkit-user-select: none;
          user-select: none;
          transition: transform 0.2s ease-in;
          transform-origin: top center;
        }

        body.mobile-native.selection-allowed a,
        body.mobile-native.selection-allowed .ProseMirror {
          user-select: auto;
          -webkit-user-select: auto;
        }

        colgroup {
          display: none;
        }

        img {
          height: auto !important;
        }

        .mceWidthContainer {
          max-width: 660px !important;
        }

        .mceColumn {
          display: block !important;
          width: 100% !important;
        }

        .mceColumn-forceSpan {
          display: table-cell !important;
          width: auto !important;
        }

        .mceBlockContainer {
          padding-right: 16px !important;
          padding-left: 16px !important;
        }

        .mceSpacing-24 {
          padding-right: 16px !important;
          padding-left: 16px !important;
        }

        .mceFooterSection .mceText,
        .mceFooterSection .mceText p {
          font-size: 16px !important;
          line-height: 140% !important;
        }

        .mceText,
        .mceText p {
          font-size: 16px !important;
          line-height: 140% !important;
        }

        h1 {
          font-size: 30px !important;
          line-height: 120% !important;
        }

        h2 {
          font-size: 26px !important;
          line-height: 120% !important;
        }

        h3 {
          font-size: 20px !important;
          line-height: 125% !important;
        }

        h4 {
          font-size: 18px !important;
          line-height: 125% !important;
        }

        .ProseMirror {
          -webkit-user-modify: read-write-plaintext-only;
          user-modify: read-write-plaintext-only;
        }
      }

      @media only screen and (max-width: 640px) {
        .mceClusterLayout td {
          padding: 4px !important;
        }
      }

      div[contenteditable="true"] {
        outline: 0;
      }

      .ProseMirror .empty-node,
      .ProseMirror:empty {
        position: relative;
      }

      .ProseMirror .empty-node::before,
      .ProseMirror:empty::before {
        position: absolute;
        left: 0;
        right: 0;
        color: rgba(0, 0, 0, 0.2);
        cursor: text;
      }

      .ProseMirror .empty-node:hover::before,
      .ProseMirror:empty:hover::before {
        color: rgba(0, 0, 0, 0.3);
      }

      .ProseMirror h1.empty-node::before {
        content: 'Heading';
      }

      .ProseMirror p.empty-node:only-child::before,
      .ProseMirror:empty::before {
        content: 'Start typing...';
      }

      a .ProseMirror p.empty-node::before,
      a .ProseMirror:empty::before {
        content: '';
      }

      .mceText,
      .ProseMirror {
        white-space: pre-wrap;
      }

      body,
      #bodyTable {
        background-color: rgb(244, 244, 244);
      }

      .mceText,
      .mceLabel {
        font-family: "Helvetica Neue", Helvetica, Arial, Verdana, sans-serif;
      }

      .mceText,
      .mceLabel {
        color: rgb(0, 0, 0);
      }

      .mceText p {
        margin-bottom: 0px;
      }

      .mceText label {
        margin-bottom: 0px;
      }

      .mceText input {
        margin-bottom: 0px;
      }

      .mceSpacing-12 .mceInput+.mceErrorMessage {
        margin-top: -6px;
      }

      .mceText p {
        margin-bottom: 0px;
      }

      .mceText label {
        margin-bottom: 0px;
      }

      .mceText input {
        margin-bottom: 0px;
      }

      .mceSpacing-24 .mceInput+.mceErrorMessage {
        margin-top: -12px;
      }

      .mceText p {
        margin-bottom: 0px;
      }

      .mceText label {
        margin-bottom: 0px;
      }

      .mceText input {
        margin-bottom: 0px;
      }

      .mceSpacing-48 .mceInput+.mceErrorMessage {
        margin-top: -24px;
      }

      .mceInput {
        background-color: transparent;
        border: 2px solid rgb(208, 208, 208);
        width: 60%;
        color: rgb(77, 77, 77);
        display: block;
      }

      .mceInput[type="radio"],
      .mceInput[type="checkbox"] {
        float: left;
        margin-right: 12px;
        display: inline;
        width: auto !important;
      }

      .mceLabel>.mceInput {
        margin-bottom: 0px;
        margin-top: 2px;
      }

      .mceLabel {
        display: block;
      }

      .mceText p {
        color: rgb(0, 0, 0);
        font-family: "Helvetica Neue", Helvetica, Arial, Verdana, sans-serif;
        font-size: 16px;
        font-weight: normal;
        line-height: 1.5;
        text-align: left;
        letter-spacing: 0px;
        direction: ltr;
      }

      @media only screen and (max-width: 480px) {
        .mceText p {
          font-size: 16px !important;
          line-height: 1.5 !important;
        }
      }

      @media only screen and (max-width: 480px) {
        .mceBlockContainer {
          padding-left: 16px !important;
          padding-right: 16px !important;
        }
      }

      #dataBlockId-9 p,
      #dataBlockId-9 h1,
      #dataBlockId-9 h2,
      #dataBlockId-9 h3,
      #dataBlockId-9 h4,
      #dataBlockId-9 ul {
        text-align: center;
      }

      @media only screen and (max-width: 480px) {
        .mobileClass-3 {
          padding-left: 12 !important;
          padding-top: 0 !important;
          padding-right: 12 !important;
        }

        .mobileClass-3 {
          padding-left: 12 !important;
          padding-top: 0 !important;
          padding-right: 12 !important;
        }

        .mobileClass-3 {
          padding-left: 12 !important;
          padding-top: 0 !important;
          padding-right: 12 !important;
        }

        .mobileClass-3 {
          padding-left: 12 !important;
          padding-top: 0 !important;
          padding-right: 12 !important;
        }
      }
    </style>
  </head>
  <body>
    <!--
-->
    <center>
      <table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%" id="bodyTable" style="background-color: rgb(244, 244, 244);">
        <tbody>
          <tr>
            <td class="bodyCell" align="center" valign="top">
              <table id="root" border="0" cellpadding="0" cellspacing="0" width="100%">
                <tbody data-block-id="13" class="mceWrapper">
                  <tr>
                    <td align="center" valign="top" class="mceWrapperOuter">
                      <!--[if (gte mso 9)|(IE)]>
											<table align="center" border="0" cellspacing="0" cellpadding="0" width="660" style="width:660px;">
												<tr>
													<td>
														<![endif]-->
                      <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width:660px" role="presentation">
                        <tbody>
                          <tr>
                            <td style="background-color:#ffffff;background-position:center;background-repeat:no-repeat;background-size:cover" class="mceWrapperInner" valign="top">
                              <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" role="presentation" data-block-id="12">
                                <tbody>
                                  <tr class="mceRow">
                                    <td style="background-position:center;background-repeat:no-repeat;background-size:cover" valign="top">
                                      <table border="0" cellpadding="0" cellspacing="0" width="100%" role="presentation">
                                        <tbody>
                                          <tr>
                                            <td style="padding-top:0;padding-bottom:0" class="mceColumn" data-block-id="-11" valign="top" colspan="12" width="100%">
                                              <table border="0" cellpadding="0" cellspacing="0" width="100%" role="presentation">
                                                <tbody>
                                                  <tr>
                                                    <td style="background-color:transparent;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0" class="mceBlockContainer" valign="top">
                                                      <table border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color:transparent" role="presentation" data-block-id="2">
                                                        <tbody>
                                                          <tr>
                                                            <td style="min-width:100%;border-top:20px solid transparent" valign="top"></td>
                                                          </tr>
                                                        </tbody>
                                                      </table>
                                                    </td>
                                                  </tr>
                                                  <tr>
                                                    <td style="background-color:#ffffff;padding-top:12px;padding-bottom:12px;padding-right:48px;padding-left:48px" class="mceBlockContainer" align="center" valign="top">
                                                      <img data-block-id="3" width="158" style="width:158px;height:auto;max-width:100%;display:block" alt="Logo" src="https://dim.mcusercontent.com/cs/461457a0cf135af0801102875/images/29832200-aaa1-d596-afc6-f0711d86bcdf.jpg?w=158&dpr=2" class="" />
                                                    </td>
                                                  </tr>
                                                  <tr>
                                                    <td style="padding-top:12px;padding-bottom:12px;padding-right:24px;padding-left:24px" class="mceBlockContainer" valign="top">
                                                      <div data-block-id="5" class="mceText" id="dataBlockId-5" style="width:100%">
                                                        <p style="text-align: center;">
                                                          <strong>
                                                            <span style="font-size: 22px">გამარჯობა 💗</span>
                                                          </strong>
                                                        </p>
                                                        <p style="text-align: center;"></p>
                                                        <p style="text-align: center;" class="last-child">შენ უკვე ESCARD-ის წევრი ხარ! <br />
                                                        </p>
                                                      </div>
                                                    </td>
                                                  </tr>
                                                  <tr>
                                                    <td style="padding-top:0px;padding-bottom:0px;padding-right:0;padding-left:0" class="mceBlockContainer" align="full" valign="top">
                                                      <img data-block-id="18" width="660" style="width:660px;height:auto;max-width:100%;display:block" alt="" src="https://dashboard.escard.ge/general/mailCover.png" role="presentation" class="imageDropZone" />
                                                    </td>
                                                  </tr>
                                                  <tr>
                                                    <td style="padding-top:0px;padding-bottom:12px;padding-right:24px;padding-left:24px" class="mceBlockContainer" valign="top">
                                                      <div data-block-id="19" class="mceText" id="dataBlockId-19" style="width:100%">
                                                        <p style="text-align: center;"></p>
                                                        <p style="text-align: center; margin-top:-35px;">აპლიკაციაში ავტორიზაციისთვის გამოიყენე შენი მეილი და პაროლი</p>
                                                        <p style="text-align: center;">
                                                          <br />
                                                        </p>
                                                        <!--<p style="text-align: center;">-->
                                                        <!--  <strong>E-mail: {{$email}}</strong>-->
                                                        <!--</p>-->
                                                        <!--<p style="text-align: center;" class="last-child">-->
                                                        <!--  <strong>Password: {{ $password }}</strong>-->
                                                        <!--</p>-->
                                                      </div>
                                                    </td>
                                                  </tr>
                                                  <tr>
                                                    <td style="background-color:transparent;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0" class="mceBlockContainer" valign="top">
                                                      <table border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color:transparent" role="presentation" data-block-id="21">
                                                        <tbody>
                                                          <tr>
                                                            <td style="min-width:100%;border-top:20px solid transparent" valign="top"></td>
                                                          </tr>
                                                        </tbody>
                                                      </table>
                                                    </td>
                                                  </tr>
                                                  <tr>
                                                    <td style="background-color:transparent;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0" class="mceBlockContainer" valign="top">
                                                      <table border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color:transparent" role="presentation" data-block-id="22">
                                                        <tbody>
                                                          <tr>
                                                            <td style="min-width:100%;border-top:20px solid transparent" valign="top"></td>
                                                          </tr>
                                                        </tbody>
                                                      </table>
                                                    </td>
                                                  </tr>
                                                  <tr>
                                                    <td style="padding-top:12px;padding-bottom:12px;padding-right:0;padding-left:0" class="mceBlockContainer" align="center" valign="top">
                                                      <a href="http://apple.co/3LXf8uW" style="display:block" target="_blank" data-block-id="16">
                                                        <img width="203" style="border:0;width:203px;height:auto;max-width:100%;display:block" alt="" src="https://dim.mcusercontent.com/cs/461457a0cf135af0801102875/images/e08c598e-74ad-be99-ca1a-72eaaef3d5a3.jpg?w=203&dpr=2" role="presentation" class="imageDropZone" />
                                                      </a>
                                                    </td>
                                                  </tr>
                                                  <tr>
                                                    <td style="background-color:transparent;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0" class="mceBlockContainer" valign="top">
                                                      <table border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color:transparent" role="presentation" data-block-id="23">
                                                        <tbody>
                                                          <tr>
                                                            <td style="min-width:100%;border-top:20px solid transparent" valign="top"></td>
                                                          </tr>
                                                        </tbody>
                                                      </table>
                                                    </td>
                                                  </tr>
                                                  <tr>
                                                    <td style="padding-top:12px;padding-bottom:12px;padding-right:0;padding-left:0" class="mceBlockContainer" align="center" valign="top">
                                                      <a href="http://bit.ly/3ptctBM" style="display:block" target="_blank" data-block-id="17">
                                                        <img width="231" style="border:0;width:231px;height:auto;max-width:100%;display:block" alt="" src="https://dim.mcusercontent.com/cs/461457a0cf135af0801102875/images/a4462bc9-d983-81aa-682f-c981ff8a7938.png?w=231&dpr=2" role="presentation" class="imageDropZone" />
                                                      </a>
                                                    </td>
                                                  </tr>
                                                  <tr>
                                                    <td style="background-color:transparent;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0" class="mceBlockContainer" valign="top">
                                                      <table border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color:transparent" role="presentation" data-block-id="27">
                                                        <tbody>
                                                          <tr>
                                                            <td style="min-width:100%;border-top:20px solid transparent" valign="top"></td>
                                                          </tr>
                                                        </tbody>
                                                      </table>
                                                    </td>
                                                  </tr>
                                                  <tr>
                                                    <td style="background-color:transparent;padding-top:20px;padding-bottom:20px;padding-right:24px;padding-left:24px" class="mceBlockContainer" valign="top">
                                                      <table border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color:transparent" role="presentation" data-block-id="26">
                                                        <tbody>
                                                          <tr>
                                                            <td style="min-width:100%;border-top:2px solid #000000" valign="top"></td>
                                                          </tr>
                                                        </tbody>
                                                      </table>
                                                    </td>
                                                  </tr>
                                                  <tr>
                                                    <td style="padding-top:12px;padding-bottom:12px;padding-right:24px;padding-left:24px" class="mceBlockContainer" align="center" valign="top">
                                                      <img data-block-id="28" width="116" style="width:116px;height:auto;max-width:100%;display:block" alt="" src="https://dim.mcusercontent.com/cs/461457a0cf135af0801102875/images/29832200-aaa1-d596-afc6-f0711d86bcdf.jpg?w=116&dpr=2" role="presentation" class="imageDropZone" />
                                                    </td>
                                                  </tr>
                                                  <tr>
                                                    <td style="background-color:transparent;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0" class="mceBlockContainer" valign="top">
                                                      <table border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color:transparent" role="presentation" data-block-id="25">
                                                        <tbody>
                                                          <tr>
                                                            <td style="min-width:100%;border-top:20px solid transparent" valign="top"></td>
                                                          </tr>
                                                        </tbody>
                                                      </table>
                                                    </td>
                                                  </tr>
                                                  <tr>
                                                    <td style="padding-top:12px;padding-bottom:12px;padding-right:0;padding-left:0" class="mceLayoutContainer" valign="top">
                                                      <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" role="presentation" data-block-id="24">
                                                        <tbody>
                                                          <tr class="mceRow">
                                                            <td style="background-position:center;background-repeat:no-repeat;background-size:cover" valign="top">
                                                              <table border="0" cellpadding="0" cellspacing="24" width="100%" role="presentation">
                                                                <tbody>
                                                                  <tr>
                                                                    <td style="margin-bottom:24px" class="mceColumn" data-block-id="-10" valign="top" colspan="12" width="100%">
                                                                      <table border="0" cellpadding="0" cellspacing="0" width="100%" role="presentation">
                                                                        <tbody>
                                                                          <tr>
                                                                            <td align="center" valign="top">
                                                                              <table border="0" cellpadding="0" cellspacing="0" width="" role="presentation" class="mceClusterLayout" data-block-id="-9">
                                                                                <tbody>
                                                                                  <tr>
                                                                                    <td style="padding-left:24px;padding-top:0;padding-right:24px" data-breakpoint="3" valign="top" class="mobileClass-3">
                                                                                      <a href="https://escard.ge/" style="display:block" target="_blank" data-block-id="-5">
                                                                                        <img width="40" style="border:0;width:40px;height:auto;max-width:100%;display:block" alt="Website icon" src="https://dim.mcusercontent.com/https/cdn-images.mailchimp.com%2Ficons%2Fsocial-block-v3%2Fblock-icons-v3%2Fwebsite-outline-dark-40.png?w=40&dpr=2" class="" />
                                                                                      </a>
                                                                                    </td>
                                                                                    <td style="padding-left:24px;padding-top:0;padding-right:24px" data-breakpoint="3" valign="top" class="mobileClass-3">
                                                                                      <a href="https://www.facebook.com/escard.community" style="display:block" target="_blank" data-block-id="-6">
                                                                                        <img width="40" style="border:0;width:40px;height:auto;max-width:100%;display:block" alt="Facebook icon" src="https://dim.mcusercontent.com/https/cdn-images.mailchimp.com%2Ficons%2Fsocial-block-v3%2Fblock-icons-v3%2Ffacebook-outline-dark-40.png?w=40&dpr=2" class="" />
                                                                                      </a>
                                                                                    </td>
                                                                                    <td style="padding-left:24px;padding-top:0;padding-right:24px" data-breakpoint="3" valign="top" class="mobileClass-3">
                                                                                      <a href="https://instagram.com/escard.community" style="display:block" target="_blank" data-block-id="-7">
                                                                                        <img width="40" style="border:0;width:40px;height:auto;max-width:100%;display:block" alt="Instagram icon" src="https://dim.mcusercontent.com/https/cdn-images.mailchimp.com%2Ficons%2Fsocial-block-v3%2Fblock-icons-v3%2Finstagram-outline-dark-40.png?w=40&dpr=2" class="" />
                                                                                      </a>
                                                                                    </td>
                                                                                    <td style="padding-left:24px;padding-top:0;padding-right:24px" data-breakpoint="3" valign="top" class="mobileClass-3">
                                                                                      <a href="https://www.linkedin.com/company/escard/" style="display:block" target="_blank" data-block-id="-8">
                                                                                        <img width="40" style="border:0;width:40px;height:auto;max-width:100%;display:block" alt="LinkedIn icon" src="https://dim.mcusercontent.com/https/cdn-images.mailchimp.com%2Ficons%2Fsocial-block-v3%2Fblock-icons-v3%2Flinkedin-outline-dark-40.png?w=40&dpr=2" class="" />
                                                                                      </a>
                                                                                    </td>
                                                                                  </tr>
                                                                                </tbody>
                                                                              </table>
                                                                            </td>
                                                                          </tr>
                                                                        </tbody>
                                                                      </table>
                                                                    </td>
                                                                  </tr>
                                                                </tbody>
                                                              </table>
                                                            </td>
                                                          </tr>
                                                        </tbody>
                                                      </table>
                                                    </td>
                                                  </tr>
                                                  <tr>
                                                    <td style="padding-top:8px;padding-bottom:8px;padding-right:8px;padding-left:8px" class="mceLayoutContainer" valign="top">
                                                      <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" role="presentation" data-block-id="11" id="section_c94ee0eafb6b02ceb166872738b736d6" class="mceFooterSection">
                                                        <tbody>
                                                          <tr class="mceRow">
                                                            <td style="background-position:center;background-repeat:no-repeat;background-size:cover" valign="top">
                                                              <table border="0" cellpadding="0" cellspacing="12" width="100%" role="presentation">
                                                                <tbody>
                                                                  <tr>
                                                                    <td style="padding-top:0;padding-bottom:0;margin-bottom:12px" class="mceColumn" data-block-id="-3" valign="top" colspan="12" width="100%">
                                                                      <table border="0" cellpadding="0" cellspacing="0" width="100%" role="presentation">
                                                                        <tbody>
                                                                          <tr>
                                                                            <td style="padding-top:12px;padding-bottom:12px;padding-right:16px;padding-left:16px" class="mceBlockContainer" align="center" valign="top">
                                                                              <div data-block-id="9" class="mceText" id="dataBlockId-9" style="display:inline-block;width:100%">
                                                                                <p class="last-child">
                                                                                  <br />
                                                                                </p>
                                                                              </div>
                                                                            </td>
                                                                          </tr>
                                                                          <tr>
                                                                            <td class="mceLayoutContainer" align="center" valign="top">
                                                                              <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" role="presentation" data-block-id="-2">
                                                                                <tbody>
                                                                                  <tr class="mceRow">
                                                                                    <td style="background-position:center;background-repeat:no-repeat;background-size:cover;padding-top:0px;padding-bottom:0px" valign="top">
                                                                                      <table border="0" cellpadding="0" cellspacing="24" width="100%" role="presentation">
                                                                                        <tbody></tbody>
                                                                                      </table>
                                                                                    </td>
                                                                                  </tr>
                                                                                </tbody>
                                                                              </table>
                                                                            </td>
                                                                          </tr>
                                                                        </tbody>
                                                                      </table>
                                                                    </td>
                                                                  </tr>
                                                                </tbody>
                                                              </table>
                                                            </td>
                                                          </tr>
                                                        </tbody>
                                                      </table>
                                                    </td>
                                                  </tr>
                                                </tbody>
                                              </table>
                                            </td>
                                          </tr>
                                        </tbody>
                                      </table>
                                    </td>
                                  </tr>
                                </tbody>
                              </table>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                      <!--[if (gte mso 9)|(IE)]>
													</td>
												</tr>
											</table>
											<![endif]-->
                    </td>
                  </tr>
                </tbody>
              </table>
            </td>
          </tr>
        </tbody>
      </table>
    </center>
    <center>
      <br />
      <br />
      <table border="0" cellpadding="0" cellspacing="0" width="100%" id="canspamBarWrapper" style="background-color:#FFFFFF; border-top:1px solid #E5E5E5;">
        <tr>
          <td align="center" valign="top" style="padding-top:20px; padding-bottom:20px;">
            <table border="0" cellpadding="0" cellspacing="0" id="canspamBar">
              <tr>
                <td align="center" valign="top" style="color:#606060; font-family:Helvetica, Arial, sans-serif; font-size:11px; line-height:150%; padding-right:20px; padding-bottom:5px; padding-left:20px; text-align:center;"> This email was sent to <a href="mailto:{{$email}}" target="_blank" style="color:#404040 !important;">{{$email}}</a>
                  <br />
                  <a href="" target="_blank" style="color:#404040 !important;">
                    <em>why did I get this?</em>
                  </a>&nbsp;&nbsp;&nbsp;&nbsp; <a href="" style="color:#404040 !important;">unsubscribe from this list</a>&nbsp;&nbsp;&nbsp;&nbsp; <a href="" style="color:#404040 !important;">update subscription preferences</a>
                  <br />
                </td>
              </tr>
            </table>
          </td>
        </tr>
      </table>
      <style type="text/css">
        @media only screen and (max-width: 480px) {
          table#canspamBar td {
            font-size: 14px !important;
          }

          table#canspamBar td a {
            display: block !important;
            margin-top: 10px !important;
          }
        }
      </style>
    </center>
  </body>
</html>