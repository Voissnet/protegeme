'use strict';

var janus = null;
var sipcall = null;
var opaqueId = "siptest-" + Janus.randomString(12);

var registered = false;
var masterId = null;

var localTracks = {}, localVideos = 0,
   remoteTracks = {}, remoteVideos = 0;

var incoming = null;

// activa janus
async function loadJanus(response) {

   const numcc = response.data['num_cc'].replace(/^.{2}/, '');
   const domainsip = response.data['domain_sip'];

   // aca debería ir el código del boton webrtc....
   Janus.init({

      debug: false, callback: function () {

         // Iniciando el proceso de Registro SIP....

         // Revisamos que el Browser soporte webRTC
         if (!Janus.isWebrtcSupported()) {
            bootbox.alert("No WebRTC support... ");
            return;
         }

         // Creo la sesión de Janus
         janus = new Janus({

            server: server,
            iceServers: iceServers,
            // Should the Janus API require authentication, you can specify either the API secret or user token here too
            //		token: "mytoken",
            //	or
            //		apisecret: "serversecret",

            success: function () {

               // Attach to SIP plugin
               janus.attach({

                  plugin: "janus.plugin.sip",
                  opaqueId: opaqueId,

                  success: function (pluginHandle) {

                     sipcall = pluginHandle;

                     Janus.log("Plugin attached! (" + sipcall.getPlugin() + ", id=" + sipcall.getId() + ")");

                     // Construimos el mensaje de Registro.
                     // construye mensaje de registro
                     const register = {
                        "request": "register",
                        "username": `sip:${response.data['sip_username']}@${domainsip}`,
                        "auth_username": response.data['sip_username'],
                        "display_name": response.data['name'],
                        "secret": response.data['sip_password'],
                        "proxy": `sip:${domainsip}:5060`
                     };

                     // Enviamos el mensaje de Registro
                     sipcall.send({ message: register });

                  },

                  error: function (error) {

                     Janus.error("  -- Error attaching plugin...", error);
                     bootbox.alert("  -- Error attaching plugin... " + error);

                  },

                  consentDialog: function (on) {

                     Janus.debug("Consent dialog should be " + (on ? "on" : "off") + " now");
                     /*    if(on) {
                             // Darken screen and show hint
                             $.blockUI({
                                 message: '<div><img src="up_arrow.png"/></div>',
                                 baseZ: 3001,
                                 css: {
                                     border: 'none',
                                     padding: '15px',
                                     backgroundColor: 'transparent',
                                     color: '#aaa',
                                     top: '10px',
                                     left: '100px'
                                 } });
                         } else {
                             // Restore screen
                             $.unblockUI();
                         } */
                  },

                  iceState: function (state) {
                     Janus.log("ICE state changed to " + state);
                  },

                  mediaState: function (medium, on, mid) {
                     Janus.log("Janus " + (on ? "started" : "stopped") + " receiving our " + medium + " (mid=" + mid + ")");
                  },

                  webrtcState: function (on) {
                     Janus.log("Janus says our WebRTC PeerConnection is " + (on ? "up" : "down") + " now");
                  },

                  slowLink: function (uplink, lost, mid) {
                     Janus.warn("Janus reports problems " + (uplink ? "sending" : "receiving") +
                        " packets on mid " + mid + " (" + lost + " lost packets)");
                  },

                  onmessage: function (msg, jsep) {

                     Janus.debug(" ::: Got a message :::", msg);

                     // Any error?
                     let error = msg["error"];

                     if (error) {

                        /*  // queda por entender este pedazo de código
                        if(!registered) {
                            $('#server').removeAttr('disabled');
                            $('#username').removeAttr('disabled');
                            $('#authuser').removeAttr('disabled');
                            $('#displayname').removeAttr('disabled');
                            $('#password').removeAttr('disabled');
                            $('#register').removeAttr('disabled').click(registerUsername);
                            $('#registerset').removeAttr('disabled');
                        } else {
                            // Reset status
                            sipcall.hangup();
                            $('#dovideo').removeAttr('disabled').val('');
                            $('#peer').removeAttr('disabled').val('');
                            $('#call').removeAttr('disabled').html('Call')
                                .removeClass("btn-danger").addClass("btn-success")
                                .unbind('click').click(doCall);
                        }*/

                        bootbox.alert(error);
                        return;

                     }

                     let callId = msg["call_id"];
                     let result = msg["result"];

                     if (result && result["event"]) {

                        let event = result["event"];

                        if (event === 'registration_failed') {

                           Janus.warn("Registration failed: " + result["code"] + " " + result["reason"]);

                           /*$('#server').removeAttr('disabled');
                           $('#username').removeAttr('disabled');
                           $('#authuser').removeAttr('disabled');
                           $('#displayname').removeAttr('disabled');
                           $('#password').removeAttr('disabled');
                           $('#register').removeAttr('disabled').click(registerUsername);
                           $('#registerset').removeAttr('disabled');*/
                           //bootbox.alert(result["code"] + " " + result["reason"]);

                           return;

                        }

                        if (event === 'registered') {

                           Janus.log("Successfully registered as " + result["username"] + "!");

                           $('#state-register').removeClass('hide').html("Ready to call");

                           // TODO Enable buttons to call now
                           if (!registered) {

                              registered = true;
                              masterId = result["master_id"];

                              /*$('#server').parent().addClass('hide');
                              $('#authuser').parent().addClass('hide');
                              $('#displayname').parent().addClass('hide');
                              $('#password').parent().addClass('hide');
                              $('#register').parent().addClass('hide');
                              $('#registerset').parent().addClass('hide');
                              $('#addhelper').removeClass('hide').click(addHelper);
                              $('#phone').removeClass('invisible').removeClass('hide');
                              $('#call').unbind('click').click(doCall);
                              $('#peer').focus();*/

                              $('#btn-emergency').attr('disabled', false);
                              $('#btn-emergency').on("click", function () {
                                 doCall(numcc, domainsip);
                              });

                           }
                        } else if (event === 'calling') {

                           Janus.log("Waiting for the peer to answer...");

                           // TODO Any ringtone?
                           $('#videoright').append(`<audio src="${url_webrtc}/src/audio/callRing.wav" class="hide" id="ringback" autoplay loop playsinline/>`);

                           //$('#btn-emergency').removeAttr('disabled').html('Hangup')
                           //.removeClass("btn-danger").addClass("btn-danger")
                           //.unbind('click').click(doHangup);

                           $('#btn-emergency').attr('src', `${url_webrtc}/src/img/isotipo_prtgm_alerting.png`).unbind('click').click(doHangup);
                           //$('#btn-emergency').on("click", function() {
                           //    doHangup;
                           //});

                           $('#state-register').html("Llamando ...");

                        } else if (event === 'incomingcall') {

                           Janus.log("Incoming call from " + result["username"] + "!");

                           sipcall.callId = callId;

                           let doAudio = true, doVideo = true;
                           let offerlessInvite = false;

                           if (jsep) {

                              // What has been negotiated?
                              doAudio = (jsep.sdp.indexOf("m=audio ") > -1);
                              doVideo = (jsep.sdp.indexOf("m=video ") > -1);
                              Janus.debug("Audio " + (doAudio ? "has" : "has NOT") + " been negotiated");
                              Janus.debug("Video " + (doVideo ? "has" : "has NOT") + " been negotiated");

                           } else {

                              Janus.log("This call doesn't contain an offer... we'll need to provide one ourselves");
                              offerlessInvite = true;
                              // In case you want to offer video when reacting to an offerless call, set this to true
                              doVideo = false;

                           }

                           // Is this the result of a transfer?
                           let transfer = "";
                           let referredBy = result["referred_by"];

                           if (referredBy) {

                              transfer = " (referred by " + referredBy + ")";
                              transfer = transfer.replace(new RegExp('<', 'g'), '&lt');
                              transfer = transfer.replace(new RegExp('>', 'g'), '&gt');

                           }

                           // Any security offered? A missing "srtp" attribute means plain RTP
                           let rtpType = "";
                           let srtp = result["srtp"];

                           if (srtp === "sdes_optional") {
                              rtpType = " (SDES-SRTP offered)";
                           } else if (srtp === "sdes_mandatory") {
                              rtpType = " (SDES-SRTP mandatory)";
                           }

                           // Notify user
                           bootbox.hideAll();

                           let extra = "";

                           if (offerlessInvite) {
                              extra = " (no SDP offer provided)";
                           }

                           incoming = bootbox.dialog({

                              message: "Incoming call from " + result["username"] + "!" + transfer + rtpType + extra,
                              title: "Incoming call",
                              closeButton: false,
                              buttons: {

                                 success: {

                                    label: "Answer",
                                    className: "btn-success",
                                    callback: function () {

                                       incoming = null;

                                       $('#peer').val(result["username"]).attr('disabled', true);

                                       // Notice that we can only answer if we got an offer: if this was
                                       // an offerless call, we'll need to create an offer ourselves
                                       let sipcallAction = (offerlessInvite ? sipcall.createOffer : sipcall.createAnswer);

                                       // We want bidirectional audio and/or video
                                       let tracks = [];

                                       if (doAudio) {
                                          tracks.push({ type: 'audio', capture: true, recv: true });
                                       }
                                       if (doVideo) {
                                          tracks.push({ type: 'video', capture: true, recv: true });
                                       }

                                       sipcallAction({

                                          jsep: jsep,
                                          tracks: tracks,

                                          success: function (jsep) {

                                             Janus.debug("Got SDP " + jsep.type + "! audio=" + doAudio + ", video=" + doVideo + ":", jsep);
                                             sipcall.doAudio = doAudio;
                                             sipcall.doVideo = doVideo;
                                             let body = { request: "accept" };

                                             // Note: as with "call", you can add a "srtp" attribute to
                                             // negotiate/mandate SDES support for this incoming call.
                                             // The default behaviour is to automatically use it if
                                             // the caller negotiated it, but you may choose to require
                                             // SDES support by setting "srtp" to "sdes_mandatory", e.g.:
                                             //		let body = { request: "accept", srtp: "sdes_mandatory" };
                                             // This way you'll tell the plugin to accept the call, but ONLY
                                             // if SDES is available, and you don't want plain RTP. If it
                                             // is not available, you'll get an error (452) back. You can
                                             // also specify the SRTP profile to negotiate by setting the
                                             // "srtp_profile" property accordingly (the default if not
                                             // set in the request is "AES_CM_128_HMAC_SHA1_80")
                                             // Note 2: by default, the SIP plugin auto-answers incoming
                                             // re-INVITEs, without involving the browser/client: this is
                                             // for backwards compatibility with older Janus clients that
                                             // may not be able to handle them. Since we want to receive
                                             // re-INVITES to handle them ourselves, we specify it here:

                                             body["autoaccept_reinvites"] = false;

                                             sipcall.send({ message: body, jsep: jsep });

                                             $('#btn-emergency').removeAttr('disabled').html('Hangup')
                                                .removeClass("btn-danger").addClass("btn-danger")
                                                .unbind('click').click(doHangup);

                                          },

                                          error: function (error) {

                                             Janus.error("WebRTC error:", error);
                                             bootbox.alert("WebRTC error... " + error.message);
                                             // Don't keep the caller waiting any longer, but use a 480 instead of the default 486 to clarify the cause
                                             let body = { request: "decline", code: 480 };
                                             sipcall.send({ message: body });

                                          }

                                       });

                                    }

                                 },

                                 danger: {

                                    label: "Decline",
                                    className: "btn-danger",

                                    callback: function () {
                                       incoming = null;
                                       let body = { request: "decline" };
                                       sipcall.send({ message: body });
                                    }

                                 }

                              }

                           });

                        } else if (event === 'accepting') {

                           // Response to an offerless INVITE, let's wait for an 'accepted'

                        } else if (event === 'progress') {

                           Janus.log("There's early media from " + result["username"] + ", waiting for the call!", jsep);
                           // Call can start already: handle the remote answer
                           if (jsep) {
                              sipcall.handleRemoteJsep({ jsep: jsep, error: doHangup });
                           }
                           toastr.info("Early media...");

                        } else if (event === 'accepted') {

                           Janus.log(result["username"] + " accepted the call!", jsep);

                           $('#ringback').remove();
                           $('#btn-emergency').attr('src', `${url_webrtc}/src/img/isotipo_prtgm_answer.png`);

                           // Call can start, now: handle the remote answer
                           if (jsep) {
                              sipcall.handleRemoteJsep({ jsep: jsep, error: doHangup });
                           }

                           //toastr.success("Call accepted!");
                           sipcall.callId = callId;

                           $('#state-register').html("Llamada establecida");

                        } else if (event === 'updatingcall') {

                           // We got a re-INVITE: while we may prompt the user (e.g.,
                           // to notify about media changes), to keep things simple
                           // we just accept the update and send an answer right away
                           Janus.log("Got re-INVITE");

                           let doAudio = (jsep.sdp.indexOf("m=audio ") > -1),
                              doVideo = (jsep.sdp.indexOf("m=video ") > -1);

                           // We want bidirectional audio and/or video, but only
                           // populate tracks if we weren't sending something before
                           let tracks = [];

                           if (doAudio && !sipcall.doAudio) {
                              sipcall.doAudio = true;
                              tracks.push({ type: 'audio', capture: true, recv: true });
                           }

                           if (doVideo && !sipcall.doVideo) {
                              sipcall.doVideo = true;
                              tracks.push({ type: 'video', capture: true, recv: true });
                           }

                           sipcall.createAnswer({

                              jsep: jsep,
                              tracks: tracks,

                              success: function (jsep) {

                                 Janus.debug("Got SDP " + jsep.type + "! audio=" + doAudio + ", video=" + doVideo + ":", jsep);
                                 let body = { request: "update" };
                                 sipcall.send({ message: body, jsep: jsep });

                              },

                              error: function (error) {

                                 Janus.error("WebRTC error:", error);
                                 bootbox.alert("WebRTC error... " + error.message);

                              }

                           });

                        } else if (event === 'message') {

                           // We got a MESSAGE
                           let sender = result["displayname"] ? result["displayname"] : result["sender"];
                           let content = result["content"];

                           content = content.replace(new RegExp('<', 'g'), '&lt');
                           content = content.replace(new RegExp('>', 'g'), '&gt');
                           toastr.success(content, "Message from " + sender);

                        } else if (event === 'info') {

                           // We got an INFO
                           let sender = result["displayname"] ? result["displayname"] : result["sender"];
                           let content = result["content"];
                           content = content.replace(new RegExp('<', 'g'), '&lt');
                           content = content.replace(new RegExp('>', 'g'), '&gt');
                           toastr.info(content, "Info from " + sender);

                        } else if (event === 'notify') {

                           // We got a NOTIFY
                           let notify = result["notify"];
                           let content = result["content"];
                           toastr.info(content, "Notify (" + notify + ")");

                        } else if (event === 'transfer') {

                           // We're being asked to transfer the call, ask the user what to do
                           let referTo = result["refer_to"];
                           let referredBy = result["referred_by"] ? result["referred_by"] : "an unknown party";
                           let referId = result["refer_id"];
                           let replaces = result["replaces"];
                           let extra = ("referred by " + referredBy);

                           if (replaces) {
                              extra += (", replaces call-ID " + replaces);
                           }

                           extra = extra.replace(new RegExp('<', 'g'), '&lt');
                           extra = extra.replace(new RegExp('>', 'g'), '&gt');

                           bootbox.confirm("Transfer the call to " + referTo + "? (" + extra + ")",
                              function (result) {

                                 if (result) {

                                    // Call the person we're being transferred to
                                    if (!sipcall.webrtcStuff.pc) {

                                       // Do it here
                                       $('#peer').val(referTo).attr('disabled', true);
                                       actuallyDoCall(sipcall, referTo, false, referId);

                                    } else {

                                       // We're in a call already, use a helper
                                       let h = -1;
                                       if (Object.keys(helpers).length > 0) {

                                          // See if any of the helpers if available
                                          for (let i in helpers) {

                                             if (!helpers[i].sipcall.webrtcStuff.pc) {
                                                h = parseInt(i);
                                                break;
                                             }

                                          }

                                       }

                                       if (h !== -1) {

                                          // Do in this helper
                                          $('#peer' + h).val(referTo).attr('disabled', true);
                                          actuallyDoCall(helpers[h].sipcall, referTo, false, referId);

                                       } else {

                                          // Create a new helper
                                          addHelper(function (id) {
                                             // Do it here
                                             $('#peer' + id).val(referTo).attr('disabled', true);
                                             actuallyDoCall(helpers[id].sipcall, referTo, false, referId);
                                          });

                                       }

                                    }

                                 } else {

                                    // We're rejecting the transfer
                                    let body = { request: "decline", refer_id: referId };
                                    sipcall.send({ message: body });

                                 }

                              });

                        } else if (event === 'hangup') {

                           if (incoming != null) {

                              incoming.modal('hide');
                              incoming = null;

                           }

                           Janus.log("Call hung up (" + result["code"] + " " + result["reason"] + ")!");

                           bootbox.alert(result["code"] + " " + result["reason"]);

                           // Reset status
                           sipcall.hangup();

                           //$('#dovideo').removeAttr('disabled').val('');
                           //$('#peer').removeAttr('disabled').val('');
                           $('#state-register').html("Ready to call");
                           $('#btn-emergency').attr('src', `${url_webrtc}/src/img/isotipo_prtgm.png`)
                              //.removeClass("btn-danger").addClass("btn-danger")
                              .unbind('click');

                           $('#btn-emergency').on("click", function () {
                              doCall(numcc, domainsip);
                           });

                        } else if (event === 'messagedelivery') {

                           // message delivery status
                           let reason = result["reason"];
                           let code = result["code"];
                           let callid = msg['call_id'];

                           if (code == 200) {

                              toastr.success(`${callid} Delivery Status: ${code} ${reason}`);

                           } else {

                              toastr.error(`${callid} Delivery Status: ${code} ${reason}`);

                           }

                        }

                     }

                  },

                  onlocaltrack: function (track, on) {

                     Janus.debug("Local track " + (on ? "added" : "removed") + ":", track);

                  },
                  onremotetrack: function (track, mid, on) {

                     Janus.debug("Remote track (mid=" + mid + ") " + (on ? "added" : "removed") + ":", track);

                     if (!on) {

                        // Track removed, get rid of the stream and the rendering
                        $('#peervideom' + mid).remove();

                        if (track.kind === "video") {

                           remoteVideos--;

                           if (remoteVideos === 0) {

                              // No video, at least for now: show a placeholder
                              if ($('#videoright .no-video-container').length === 0) {
                                 $('#videoright').append(
                                    '<div class="no-video-container">' +
                                    '<i class="fa-solid fa-video fa-xl no-video-icon"></i>' +
                                    '<span class="no-video-text">No remote video available</span>' +
                                    '</div>');
                              }

                           }

                        }

                        delete remoteTracks[mid];
                        return;

                     }
                     // If we're here, a new track was added
                     /*if($('#videoright audio').length === 0 && $('#videoright video').length === 0) {
                         $('#videos').removeClass('hide');
                         $('#videoright').parent().find('span').html(
                             '<span id="dtmf">' +
                                 '<button type="button" class="btn btn-outline-primary btn-lg dtmf m-1">1</button>' +
                                 '<button type="button" class="btn btn-outline-primary btn-lg dtmf m-1">2</button>'+
                                 '<button type="button" class="btn btn-outline-primary btn-lg dtmf m-1">3</button>'+
                             ''+
                                 '<button type="button" class="btn btn-outline-primary btn-lg dtmf m-1">4</button>' +
                                 '<button type="button" class="btn btn-outline-primary btn-lg dtmf m-1">5</button>'+
                                 '<button type="button" class="btn btn-outline-primary btn-lg dtmf m-1">6</button>'+
                             ''+
                                 '<button type="button" class="btn btn-outline-primary btn-lg dtmf m-1">7</button>' +
                                 '<button type="button" class="btn btn-outline-primary btn-lg dtmf m-1">8</button>'+
                                 '<button type="button" class="btn btn-outline-primary btn-lg dtmf m-1">9</button>'+
                             ''+
                                 '<button type="button" class="btn btn-outline-primary btn-lg dtmf m-1">*</button>' +
                                 '<button type="button" class="btn btn-outline-primary btn-lg dtmf m-1">0</button>'+
                                 '<button type="button" class="btn btn-outline-primary btn-lg dtmf m-1">#</button>'+
                             ''+
                             '</span>'
                         );
                         /*for(let i=0; i<12; i++) {
                             if(i<10)
                                 $('#dtmf').append('<button class="btn btn-info dtmf">' + i + '</button>');
                             else if(i == 10)
                                 $('#dtmf').append('<button class="btn btn-info dtmf">#</button>');
                             else if(i == 11)
                                 $('#dtmf').append('<button class="btn btn-info dtmf">*</button>');
                         } */
                     /*$('#dtmf .dtmf').click(function() {
                         // Send DTMF tone (inband)
                         sipcall.dtmf({dtmf: { tones: $(this).text()}});
                         // Notice you can also send DTMF tones using SIP INFO
                         // 		sipcall.send({message: {request: "dtmf_info", digit: $(this).text()}});
                     });
                     $('#msg').click(function() {
                         bootbox.prompt("Insert message to send", function(result) {
                             if(result && result !== '') {
                                 // Send the message
                                 let msg = { request: "message", content: result };
                                 sipcall.send({ message: msg });
                             }
                         });
                     });
                     $('#info').click(function() {
                         bootbox.dialog({
                             message: 'Type: <input class="form-control" type="text" id="type" placeholder="e.g., application/xml">' +
                                 '<br/>Content: <input class="form-control" type="text" id="content" placeholder="e.g., <message>hi</message>">',
                             title: "Insert the type and content to send",
                             buttons: {
                                 cancel: {
                                     label: "Cancel",
                                     className: "btn-secondary",
                                     callback: function() {
                                         // Do nothing
                                     }
                                 },
                                 ok: {
                                     label: "OK",
                                     className: "btn-primary",
                                     callback: function() {
                                         // Send the INFO
                                         let type = $('#type').val();
                                         let content = $('#content').val();
                                         if(type === '' || content === '')
                                             return;
                                         let msg = { request: "info", type: type, content: content };
                                         sipcall.send({ message: msg });
                                     }
                                 }
                             }
                         });
                     });
                     $('#transfer').click(function() {
                         bootbox.dialog({
                             message: '<input class="form-control" type="text" id="transferto" placeholder="e.g., sip:goofy@example.com">',
                             title: "Insert the address to transfer the call to",
                             buttons: {
                                 cancel: {
                                     label: "Cancel",
                                     className: "btn-secondary",
                                     callback: function() {
                                         // Do nothing
                                     }
                                 },
                                 blind: {
                                     label: "Blind transfer",
                                     className: "btn-info",
                                     callback: function() {
                                         // Start a blind transfer
                                         let address = $('#transferto').val();
                                         if(address === '')
                                             return;
                                         let msg = { request: "transfer", uri: address };
                                         sipcall.send({ message: msg });
                                     }
                                 },
                                 attended: {
                                     label: "Attended transfer",
                                     className: "btn-primary",
                                     callback: function() {
                                         // Start an attended transfer
                                         let address = $('#transferto').val();
                                         if(address === '')
                                             return;
                                         // Add the call-id to replace to the transfer
                                         let msg = { request: "transfer", uri: address, replace: sipcall.callId };
                                         sipcall.send({ message: msg });
                                     }
                                 }
                             }
                         });
                     });
                 } */
                     if (track.kind === "audio") {

                        // New audio track: create a stream out of it, and use a hidden <audio> element
                        let stream = new MediaStream([track]);
                        remoteTracks[mid] = stream;
                        Janus.log("Created remote audio stream:", stream);

                        $('#videoright').append('<audio class="hide" id="peervideom' + mid + '" autoplay playsinline/>');

                        Janus.attachMediaStream($('#peervideom' + mid).get(0), stream);

                        /*if(remoteVideos === 0) {
                            // No video, at least for now: show a placeholder
                            if($('#videoright .no-video-container').length === 0) {
                                $('#videoright').append(
                                    '<div class="no-video-container">' +
                                        '<i class="fa-solid fa-video fa-xl no-video-icon"></i>' +
                                        '<span class="no-video-text">No remote video available</span>' +
                                    '</div>');
                            }
                        }*/
                     } /*else {
                                       // New video track: create a stream out of it
                                       remoteVideos++;
                                       $('#videoright .no-video-container').remove();
                                       let stream = new MediaStream([track]);
                                       remoteTracks[mid] = stream;
                                       Janus.log("Created remote video stream:", stream);
                                       $('#videoright').append('<video class="rounded centered" id="peervideom' + mid + '" width="100%" height="100%" autoplay playsinline/>');
                                       Janus.attachMediaStream($('#peervideom' + mid).get(0), stream);
                                   } */
                  },
                  oncleanup: function () {

                     Janus.log(" ::: Got a cleanup notification :::");

                     //$("#videoleft").empty().parent().unblock();

                     $('#videoright').empty();
                     $('#videos').addClass('hide');
                     $('#dtmf').parent().html("Remote UA");

                     if (sipcall) {
                        delete sipcall.callId;
                        delete sipcall.doAudio;
                        delete sipcall.doVideo;
                     }

                     localTracks = {};
                     localVideos = 0;
                     remoteTracks = {};
                     remoteVideos = 0;

                  }

               });

               //$('#btn-emergency').attr('disabled', false);

            },

            error: function (error) {

               Janus.error(error);
               bootbox.alert("Probando - " + error, function () {
                  window.location.reload();
               });

            },

            destroyed: function () {

               window.location.reload();

            }

         });


      }

   });

}

// generar llamada
function doCall(numcc, server) {

   const handle = sipcall;
   const uri = `sip:${numcc}@${server}`;

   const tracks = [{
      type: 'audio',
      capture: true,
      recv: true
   }];

   handle.createOffer({

      tracks: tracks,

      success: function (jsep) {

         Janus.debug("Got SDP!", jsep);

         let body = { request: "call", uri: uri };

         body["autoaccept_reinvites"] = false;

         handle.send({ message: body, jsep: jsep });

      },

      error: function (error) {

         Janus.error("WebRTC error...", error);
         bootbox.alert("WebRTC error... " + error.message);

      }

   });

}

// Colgar
function doHangup(ev) {

   let button = ev ? ev.currentTarget.id : "llamar";

   $('#btn-emergency').attr('disabled', true).unbind('click');

   let hangup = { request: "hangup" };
   sipcall.send({ message: hangup });
   sipcall.hangup();

}