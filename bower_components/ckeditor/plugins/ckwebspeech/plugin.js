var MIN_DB_LEVEL = -110;
var MAX_DB_LEVEL = -40;

var DB_LEVEL_RANGE = MAX_DB_LEVEL - MIN_DB_LEVEL;

var microphoneIsWiredUp = false;
var microphoneAccessIsNotAllowed = undefined;
var suppressNoise = false;
var addNoise = false;
var mediaStream = null;

var Module = null;

var audioContext;
var audio_context;
var recorder;
var instructions = $('#instructions');

function stopMicrophone() {
  if (!microphoneIsWiredUp) {
    return;
  }
  if (mediaStream) {
    mediaStream.getTracks().forEach(track => {
      track.stop();
    });
  }

  microphoneIsWiredUp = false;
}

function getMicrophoneAccess() {
  if (microphoneIsWiredUp) {
    return;
  }
  try {
    window.AudioContext = window.AudioContext || window.webkitAudioContext;
    audioContext = new AudioContext();
  } catch (e) {
    alert('Web Audio API tidak dapat digunakan pada web browser ini.');
  }

  // Check if there is microphone input.
  navigator.getUserMedia = navigator.getUserMedia ||
                           navigator.webkitGetUserMedia ||
                           navigator.mozGetUserMedia ||
                           navigator.msGetUserMedia;
  if (!navigator.getUserMedia) {
    alert("getUserMedia() tidak dapat digunakan pada web browser ini.");
    return;
  }
  var inputBuffer = [];
  var outputBuffer = [];
  var bufferSize = 16384;
  var sampleRate = audioContext.sampleRate;
  var processingNode = audioContext.createScriptProcessor(bufferSize, 1, 1);
  var noiseNode = audioContext.createScriptProcessor(bufferSize, 1, 1);

  noiseNode.onaudioprocess = function (e) {
    var input = e.inputBuffer.getChannelData(0);
    var output = e.outputBuffer.getChannelData(0);
    for (let i = 0; i < input.length; i++) {
      if (addNoise) {
        output[i] = input[i] + (Math.random() / 100);
      } else {
        output[i] = input[i];
      }
    }
  };

  function removeNoise(buffer) {
    let ptr = Module.ptr;
    let st = Module.st;
    for (let i = 0; i < 480; i++) {
      Module.HEAPF32[(ptr >> 2) + i] = buffer[i] * 32768;
    }
    Module._rnnoise_process_frame(st, ptr, ptr);
    for (let i = 0; i < 480; i++) {
      buffer[i] = Module.HEAPF32[(ptr >> 2) + i] / 32768;
    }
  }

  let frameBuffer = [];

  processingNode.onaudioprocess = function (e) {
    var input = e.inputBuffer.getChannelData(0);
    var output = e.outputBuffer.getChannelData(0);

    // Drain input buffer.
    for (let i = 0; i < bufferSize; i++) {
      inputBuffer.push(input[i]);
    }

    while (inputBuffer.length >= 480) {
      for (let i = 0; i < 480; i++) {
        frameBuffer[i] = inputBuffer.shift();
      }
      // Process Frame
      if (suppressNoise) {
        removeNoise(frameBuffer);
      }
      for (let i = 0; i < 480; i++) {
        outputBuffer.push(frameBuffer[i]);
      }
    }
    // Not enough data, exit early, etherwise the AnalyserNode returns NaNs.
    if (outputBuffer.length < bufferSize) {
      return;
    }
    // Flush output buffer.
    for (let i = 0; i < bufferSize; i++) {
      output[i] = outputBuffer.shift();
    }
  }

  // Get access to the microphone and start pumping data through the graph.
  navigator.getUserMedia({
    audio: {
      echoCancellation: false,
      noiseSuppression: false,
      autoGainControl: false,
    }
  }, function (stream) {
    mediaStream = stream;
    var microphone = audioContext.createMediaStreamSource(stream);
    var sourceAnalyserNode = audioContext.createAnalyser();
    var destinationAnalyserNode = audioContext.createAnalyser();
    sourceAnalyserNode.smoothingTimeConstant = 0;
    destinationAnalyserNode.smoothingTimeConstant = 0;

    sourceAnalyserNode.fftSize = 2048;
    destinationAnalyserNode.fftSize = 2048;

    microphone.connect(noiseNode);
    noiseNode.connect(sourceAnalyserNode);
    sourceAnalyserNode.connect(processingNode);
    processingNode.connect(destinationAnalyserNode);
    // destinationAnalyserNode.connect(audioContext.destination);

    microphoneIsWiredUp = true;

    recorder = new Recorder(processingNode);

  }, function (e) {
    if (e.name === "PermissionDeniedError") {
      microphoneAccessIsNotAllowed = true;
      alert("Anda harus mengizinkan akses mikrofon pada halaman ini.");
    }
  });
}

function convertFloat32ToInt16(buffer) {
  let l = buffer.length;
  let buf = new Int16Array(l);
  while (l--) {
    buf[l] = Math.min(1, buffer[l]) * 0x7FFF;
  }
  return buf;
}

function initializeNoiseSuppressionModule() {
  if (Module) {
    return;
  }
  Module = {
    noExitRuntime: true,
    noInitialRun: true,
    preInit: [],
    preRun: [],
    postRun: [function () {
      // console.log("RNNoise Javascript Module loaded.");
    }],
    memoryInitializerPrefixURL: "bin/",
    arguments: ['input.ivf', 'output.raw']
  };
  NoiseModule(Module);
  Module.st = Module._rnnoise_create();
  Module.ptr = Module._malloc(480 * 4);
}

var langs = [
    ["Bahasa Indonesia", ["id-ID"]],
    ["English", ["en-AU", "Australia"],
        ["en-CA", "Canada"],
        ["en-IN", "India"],
        ["en-NZ", "New Zealand"],
        ["en-ZA", "South Africa"],
        ["en-GB", "United Kingdom"],
        ["en-US", "United States"]
    ]
];
var final_transcript = '';
var CKWebSpeechCommandVoice = function(a) {
    this._editor = a;
    this._commandvoice = false;
    this._commands = false;
    this._regexpcommand = false;
    this.CKWebSpeechCommandVoice(this._editor.config)
};
CKWebSpeechCommandVoice.prototype.CKWebSpeechCommandVoice = function(a) {
    if (typeof this._editor.config.ckwebspeech == "undefined") {
        this._editor.config.ckwebspeech = {}
    }
    this.setCommands(this._editor.config)
};
CKWebSpeechCommandVoice.prototype.setCommands = function(b) {
    var c = false;
    var a = false;
    if (b.ckwebspeech.commandvoice) {
        c = b.ckwebspeech.commandvoice
    }
    if (b.ckwebspeech.commands) {
        a = this.makeCommands(b.ckwebspeech.commands)
    }
    if (c && a) {
        this._regexpcommand = new RegExp("(" + c + "){1}\\s{1}(" + a + ")", "gi");
        this._commands = b.ckwebspeech.commands;
        this._commandvoice = c
    }
};
CKWebSpeechCommandVoice.prototype.makeCommands = function(a) {
    var c = "";
    if (a instanceof Array) {
        for (var b = 0; b < a.length; b++) {
            var d = a[b];
            c += this.makeValidCommand(d)
        }
        return c == "" ? false : c.replace(/\|$/gi, "")
    } else {
        return false
    }
};
CKWebSpeechCommandVoice.prototype.makeValidCommand = function(b) {
    var c = new RegExp("(newline|newparagraph|undo|redo)");
    if (typeof b == "object") {
        for (var a in b) {
            if (c.test(a)) {
                return b[a] + "|"
            }
        }
    }
    return ""
};
CKWebSpeechCommandVoice.prototype.buildResult = function(a) {
    if (this._regexpcommand) {
        var b;
        var e = [];
        while ((b = this._regexpcommand.exec(a)) != null) {
            e.push(b[2])
        }
        for (var c = 0; c < e.length; c++) {
            var f = new String(e[c]);
            var d = this.getCommandForResult(f);
            a = this.execCmd(d, f, a)
        }
        return a
    } else {
        return a
    }
};
CKWebSpeechCommandVoice.prototype.execCmd = function(c, d, a) {
    var b = new RegExp(this._commandvoice + " " + d + "\\s?", "gi");
    switch (c) {
        case "newline":
            return a.replace(b, "\n");
        case "newparagraph":
            return a.replace(b, "\n\n");
        case "undo":
            this._editor.execCommand("undo");
            return false;
        case "redo":
            this._editor.execCommand("redo");
            return false;
        default:
            return a
    }
};
CKWebSpeechCommandVoice.prototype.getCommandForResult = function(c) {
    this._commands;
    for (var b = 0; b < this._commands.length; b++) {
        for (var a in this._commands[b]) {
            if (this._commands[b][a] == c) {
                return a
            }
        }
    }
    return ""
};
var CKWebSpeechHandler = function(a) {
    CKWebSpeechCommandVoice.call(this, a);
    this._currentCulture = {
        val: "id-ID",
        langVal: 1
    };
    this._elmtPlugIcon;
    this._plugPath;
    this._recognizing;
    this._recognition;
    this._ignore_onend;
    this._start_timestamp;
    this._working;
    this.CKWebSpeechHandler()
};
CKWebSpeechHandler.prototype = Object.create(CKWebSpeechCommandVoice.prototype);
CKWebSpeechHandler.prototype.CKWebSpeechHandler = function() {
    this._recognition;
    this._plugPath = this._editor.plugins.ckwebspeech.path;
    this._recognizing = false;
    this._ignore_onend = false;
    this._working = false;
    this.getElementPluginIcon();
    this.initServiceSpeech()
};
CKWebSpeechHandler.prototype.isUnlockedService = function() {
    if (!("webkitSpeechRecognition" in window)) {
        return false
    }
    return true
};
CKWebSpeechHandler.prototype.getElementPluginIcon = function() {
    var c = this;
    var a = 0;
    var b = setInterval(function() {
        a++;
        var d;
        try {
            d = document.getElementById(c._editor.ui.instances.webSpeechEnabled._.id)
        } catch (e) {
            d = null
        }
        if (d !== null) {
            c._elmtPlugIcon = d.getElementsByClassName("cke_button__webspeechenabled_icon")[0];
            clearInterval(b)
        }
        if (a == 500) {
            clearInterval(b)
        }
    }, 1)
};
CKWebSpeechHandler.prototype.updateIcons = function() {
    if (this._recognizing) {
        this._editor.ui.get("webSpeechEnabled").label = "Pause";
        this._editor.ui.get("webSpeechEnabled").icon = this._plugPath + "icons/webspeech.png";
        this._elmtPlugIcon.style.backgroundImage = "url(" + this._plugPath + "icons/webspeech-enable.gif)"
    } else {
        this._editor.ui.get("webSpeechEnabled").label = "Start";
        this._editor.ui.get("webSpeechEnabled").icon = this._plugPath + "icons/webspeech-enable.gif";
        this._elmtPlugIcon.style.backgroundImage = "url(" + this._plugPath + "icons/webspeech.png)"
    }
};
CKWebSpeechHandler.prototype.initServiceSpeech = function() {
    if (this.isUnlockedService()) {
        this._recognition = new webkitSpeechRecognition();
        this._recognition.continuous = true;
        this._recognition.interimResults = true;
        var a = this;
        this._recognition.onstart = function() {
            initializeNoiseSuppressionModule();
            suppressNoise = true;
            a.onStart()
        };
        this._recognition.onerror = function(b) {
            a.onError(b)
        };
        this._recognition.onend = function() {
            a.onEnd()
        };
        this._recognition.onresult = function(b) {
            a.onResult(b)
        };
        this._recognition.onspeechstart = function(b) {
            a.onSpeech()
        };
        this._recognition.onspeechend = function(b) {
            a.onSpeechEnd()
        }
    }
};
CKWebSpeechHandler.prototype.onStart = function() {
    this._recognizing = true;
    this.updateIcons()
};
CKWebSpeechHandler.prototype.onError = function(a) {
    if (a.error == "no-speech") {
        alert("Tidak ada ucapan yang terdeteksi.");
        this._ignore_onend = true;
    }
    if (a.error == "audio-capture") {
        alert("Tidak ada mikrofon yang ditemukan.");
        this._ignore_onend = true;
    }
    if (a.error == "not-allowed") {
        if (a.timeStamp - this._start_timestamp < 100) {
            alert("Izin untuk menggunakan mikrofon diblokir.");
        } else {
            alert("Izin untuk menggunakan mikrofon ditolak.");
        }
        this._ignore_onend = true;
    }
    this.updateIcons()
};
CKWebSpeechHandler.prototype.onEnd = function() {
    this._recognizing = false;
    if (this._ignore_onend) {
        return
    }
    this.updateIcons()
};
CKWebSpeechHandler.prototype.onSpeech = function(a) {
    this._elmtPlugIcon.style.backgroundImage = "url(" + this._plugPath + "icons/speech.gif)"
};
CKWebSpeechHandler.prototype.onSpeechEnd = function(a) {
    this.updateIcons()
};
CKWebSpeechHandler.prototype.onResult = function(c) {
    var interim_transcript = '';
    if (typeof(c.results) == "undefined") {
        this._recognizing = false;
        this._recognition.onend = null;
        this._recognition.stop();
        this.updateIcons();
        return
    }
    this._elmtPlugIcon.style.backgroundImage = "url(" + this._plugPath + "icons/speech.gif)";
    for (var b = c.resultIndex; b < c.results.length; ++b) {
        if (c.results[b].isFinal) {
            final_transcript += event.results[b][0].transcript;
            var a = this.buildResult(c.results[b][0].transcript);
            if (a) {
                // this._editor.insertHtml('<br />');
                this._editor.insertText(capitalize(a))
            }
            this.updateIcons()
        } else {
            interim_transcript += event.results[b][0].transcript;
        }
    }
    final_transcript = capitalize(final_transcript);
    final_span.innerHTML = linebreak(final_transcript);
    interim_span.innerHTML = linebreak(interim_transcript);
};
CKWebSpeechHandler.prototype.toogleSpeech = function() {
    if (!this._recognizing) {
        this._recognition.lang = this._currentCulture.val;
        this._recognition.start();
        this._ignore_onend = false;
        this._start_timestamp = new Date().getTime()
    } else {
        this._recognition.stop()
    }
};
var CKWebSpeech = function(a) {
    CKWebSpeechHandler.call(this, a);
    this._langs = langs;
    this.CKWebSpeech()
};
CKWebSpeech.prototype = Object.create(CKWebSpeechHandler.prototype);
CKWebSpeech.prototype.CKWebSpeech = function() {
    if (typeof this._editor.config.ckwebspeech == "undefined") {
        this._editor.config.ckwebspeech = {}
    }
    if (this._editor.config.ckwebspeech.culture) {
        this.setDialectByCulture(this._editor.config.ckwebspeech.culture)
    }
};
CKWebSpeech.prototype.setDialectByCulture = function(c) {
    for (var b = 0; b < this._langs.length; b++) {
        for (var a = 1; a < this._langs[b].length; a++) {
            if (this._langs[b][a][0].toLowerCase() == c.toLowerCase()) {
                this._currentCulture = {
                    val: this._langs[b][a][0],
                    langVal: b
                };
                return this._currentCulture
            }
        }
    }
    return this._currentCulture
};
CKWebSpeech.prototype.setDialectByLanguage = function(a) {
    this.setDialectByCulture(this._langs[a][1][0])
};
CKWebSpeech.prototype.getLanguages = function() {
    var b = new Array();
    for (var a = 0; a < this._langs.length; a++) {
        b.push(new Array(this._langs[a][0], a))
    }
    return b
};
CKWebSpeech.prototype.getCultures = function(a) {
    if (typeof a === "undefined") {
        a = this._currentCulture.langVal
    }
    var c = new Array();
    for (var b = 1; b < this._langs[a].length; b++) {
        c.push(new Array(this._langs[a][b][0]))
    }
    return c
};
CKEDITOR.plugins.add("ckwebspeech", {
    icons: "webspeech",
    init: function(b) {
        b.ckWebSpeech = new CKWebSpeech(b);
        var a = this.path;
        var shortcutToggle = CKEDITOR.ALT + 51;
        b.addCommand("webspeechDialog", new CKEDITOR.dialogCommand("webspeechDialog"));
        b.addCommand("webspeechToogle", {
            exec: function(c) {
                c.ckWebSpeech.toogleSpeech()
            }
        });
        b.ui.addButton("webSpeechEnabled", {
            label: "Mulai",
            icon: a + "icons/webspeech.png",
            command: "webspeechToogle",
            toolbar: "ckwebspeech"
        });
        b.ui.addButton("webSpeechSettings", {
            label: "Konfigurasi",
            icon: a + "icons/webspeech-settings.png",
            command: "webspeechDialog",
            toolbar: "ckwebspeech"
        });
        CKEDITOR.dialog.add("webspeechDialog", this.path + "dialogs/ckwebspeech.js");
        b.keystrokeHandler.keystrokes[shortcutToggle] = 'webspeechToogle';
    }
});

var two_line = /\n\n/g;
var one_line = /\n/g;
function linebreak(s) {
  return s.replace(two_line, '<p></p>').replace(one_line, '<br>');
}

var first_char = /\S/;
function capitalize(s) {
  return s.replace(first_char, function(m) { return m.toUpperCase(); });
}
