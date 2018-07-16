function enrollNewProfile(){
    idStatus.text('Listening...');
    boxStatus.toggleClass('box-default box-info');
    navigator.getUserMedia({audio: true}, function(stream){
        console.log('\nPlease start talking for a few seconds...');
        console.log('You can say \"Saya Moderator\" or \"Saya Responden\" three times.');
        onMediaSuccess(stream, createProfile, 15);
    }, onMediaError);
}

function enrollNewVerificationProfile(){
    navigator.getUserMedia({audio: true}, function(stream){
        console.log('Please say \"my password is not your business\".');
        onMediaSuccess(stream, createVerificationProfile, 4);
    }, onMediaError);
}

function startListeningForIdentification(){
    idStatus.text('Listening...');
    boxStatus.toggleClass('box-success box-info');
    if (profileIds.length > 0 ){
        console.log('\nPlease start talking for a few seconds...');
        console.log('You can say \"Saya Moderator\" or \"Saya Responden\" three times.');
        navigator.getUserMedia({audio: true}, function(stream){onMediaSuccess(stream, identifyProfile, 10)}, onMediaError);
    } else {
        console.log('No profiles enrolled yet! Click the Speak for Identification button first.');
    }
}

function startListeningForVerification(){
    if (verificationProfile.profileId){
        console.log('Please say \"my password is not your business\".');
        navigator.getUserMedia({audio: true}, function(stream){onMediaSuccess(stream, verifyProfile, 4)}, onMediaError);
    } else {
        console.log('No verification profile enrolled yet! Click the Speak for Verification button first.');
    }
}

function onMediaError(e) {
    console.error('Media error : ', e);
}

function identifyProfile(blob){
    addAudioPlayer(blob);

    var Ids = profileIds.map(x => x.profileId).join();
    const identify = 'https://westus.api.cognitive.microsoft.com/spid/v1.0/identify?identificationProfileIds=' + Ids + '&shortAudio=true';

    var request = new XMLHttpRequest();
    request.open("POST", identify, true);

    request.setRequestHeader('Content-Type','application/json');
    request.setRequestHeader('Ocp-Apim-Subscription-Key', key);

    request.onload = function () {
        idStatus.text('Identifying profile...');
        var location = request.getResponseHeader('Operation-Location');

        if (location!=null) {
            pollForIdentification(location);
        } else {
            idStatus.text('Sorry, we can\'t poll. There\'s an error.');
        }
    };

    request.send(blob);
}

function verifyProfile(blob){
    addAudioPlayer(blob);

    var verify = 'https://westus.api.cognitive.microsoft.com/spid/v1.0/verify?verificationProfileId=' + verificationProfile.profileId;

    var request = new XMLHttpRequest();
    request.open("POST", verify, true);

    request.setRequestHeader('Content-Type','application/json');
    request.setRequestHeader('Ocp-Apim-Subscription-Key', key);

    request.onload = function () {
        idStatus.text('Verifying profile...');
    };

    request.send(blob);
}

function createProfile(blob){
    addAudioPlayer(blob);

    var create = 'https://westus.api.cognitive.microsoft.com/spid/v1.0/identificationProfiles';

    var request = new XMLHttpRequest();
    request.open("POST", create, true);

    request.setRequestHeader('Content-Type','application/json');
    request.setRequestHeader('Ocp-Apim-Subscription-Key', key);

    request.onload = function () {
        idStatus.text('Creating profile...');

        var json = JSON.parse(request.responseText);
        var profileId = json.identificationProfileId;

        // Now we can enrol this profile using the profileId
        enrollProfileAudio(blob, profileId);
    };

    request.send(JSON.stringify({ 'locale' :'en-us'}));
}

function enrollProfileAudio(blob, profileId){
  const enroll = 'https://westus.api.cognitive.microsoft.com/spid/v1.0/identificationProfiles/'+profileId+'/enroll?shortAudio=true';

  var request = new XMLHttpRequest();
  request.open("POST", enroll, true);

  request.setRequestHeader('Content-Type','multipart/form-data');
  request.setRequestHeader('Ocp-Apim-Subscription-Key', key);

  request.onload = function () {
    idStatus.text('Enrolling...');
    var location = request.getResponseHeader('Operation-Location');

    //console.log(location);

    if (location!=null) {
        pollForEnrollment(location, profileId);
    } else {
        idStatus.text('Sorry, we can\'t poll. There\'s an error.');
    }
  };

  request.send(blob);
}

function enrollProfileAudioForVerification(blob, profileId){
    addAudioPlayer(blob);

    if (profileId == undefined)
    {
        idStatus.text("Failed to create a profile for verification. Please try again.");
        return;
    }

    const enroll = 'https://westus.api.cognitive.microsoft.com/spid/v1.0/verificationProfiles/'+profileId+'/enroll';

    var request = new XMLHttpRequest();
    request.open("POST", enroll, true);

    request.setRequestHeader('Content-Type','multipart/form-data');
    request.setRequestHeader('Ocp-Apim-Subscription-Key', key);

    request.onload = function () {
        idStatus.text('Enrolling...');

        var json = JSON.parse(request.responseText);
        verificationProfile.remainingEnrollments = json.remainingEnrollments;
        if (verificationProfile.remainingEnrollments == 0)
        {
            idStatus.text("Verification should be enabled!")
        }
    };

    request.send(blob);
  }


  function pollForEnrollment(location, profileId){
    var success = false;
    var enrolledInterval;

    enrolledInterval = setInterval(function()
    {
        var request = new XMLHttpRequest();
        request.open("GET", location, true);

        request.setRequestHeader('Content-Type','multipart/form-data');
        request.setRequestHeader('Ocp-Apim-Subscription-Key', key);

        request.onload = function()
        {
            idStatus.text('Getting status...');

            var json = JSON.parse(request.responseText);
            if (json.status == 'succeeded' && json.processingResult.enrollmentStatus == 'Enrolled')
            {
                clearInterval(enrolledInterval);
                idStatus.text('\nEnrollment complete!');
                var name = window.prompt('Who was that talking?');
                profileIds.push(new Profile(name, profileId));
                boxStatus.toggleClass('box-info box-success');
                idStatus.text(name + ' profile has been created.\n');
            }
            else if(json.status == 'succeeded' && json.processingResult.remainingEnrollmentSpeechTime > 0) {
                clearInterval(enrolledInterval);
                idStatus.text('That audio wasn\'t long enough to use.');
            }
            else
            {
                idStatus.text('Not done yet...');
                idStatus.text(json);
            }
        };

        request.send();
    }, 4000);
}

function pollForIdentification(location){
    var success = false;
    var enrolledInterval;

    enrolledInterval = setInterval(function()
    {
        var request = new XMLHttpRequest();
        request.open("GET", location, true);

        request.setRequestHeader('Content-Type','multipart/form-data');
        request.setRequestHeader('Ocp-Apim-Subscription-Key', key);

        request.onload = function()
        {
            idStatus.text('Getting status...');

            var json = JSON.parse(request.responseText);
            if (json.status == 'succeeded')
            {
                clearInterval(enrolledInterval);
                var speaker = profileIds.filter(function(p){return p.profileId == json.processingResult.identifiedProfileId});

                if (speaker != null && speaker.length > 0){
                    boxStatus.toggleClass('box-info box-success');
                    idStatus.text('\nThe speaker is ' + speaker[0].name + '.');
                } else {
                    boxStatus.toggleClass('box-info box-warning');
                    idStatus.text('Sorry, we can\'t identify the speaker.');
                }
            }
            else
            {
                idStatus.text('Please wait...');
                idStatus.text(json);
            }
        };

        request.send();
    }, 2000);
}

function createVerificationProfile(blob){

    if (verificationProfile && verificationProfile.profileId)
    {
        if (verificationProfile.remainingEnrollments == 0)
        {
            idStatus.text("Verification enrollment already completed.");
            return;
        }
        else
        {
            idStatus.text("Verification enrollments remaining: " + verificationProfile.remainingEnrollments);
            enrollProfileAudioForVerification(blob, verificationProfile.profileId);
            return;
        }
    }

    var create = 'https://westus.api.cognitive.microsoft.com/spid/v1.0/verificationProfiles';

    var request = new XMLHttpRequest();
        request.open("POST", create, true);
        request.setRequestHeader('Content-Type','application/json');
        request.setRequestHeader('Ocp-Apim-Subscription-Key', key);

        request.onload = function () {
            var json = JSON.parse(request.responseText);
            var profileId = json.verificationProfileId;
            verificationProfile.profileId = profileId;

            // Now we can enrol this profile with the profileId
            enrollProfileAudioForVerification(blob, profileId);
        };

    request.send(JSON.stringify({ 'locale' :'en-us'}));
}

function BurnItAll(mode = 'identification'){
    // brute force delete everything - keep retrying until it's empty
    var listing = 'https://westus.api.cognitive.microsoft.com/spid/v1.0/' + mode + 'Profiles';

    var request = new XMLHttpRequest();
    request.open("GET", listing, true);

    request.setRequestHeader('Content-Type','multipart/form-data');
    request.setRequestHeader('Ocp-Apim-Subscription-Key', key);

    request.onload = function () {
        var json = JSON.parse(request.responseText);
        for(var x in json){
            if (json[x][mode + 'ProfileId'] == undefined) {continue;}
            var request2 = new XMLHttpRequest();
            request2.open("DELETE", listing + '/'+ json[x][mode + 'ProfileId'], true);

            request2.setRequestHeader('Content-Type','multipart/form-data');
            request2.setRequestHeader('Ocp-Apim-Subscription-Key', key);
            request2.onload = function(){
                console.log(request2.responseText);
            };
            request2.send();
        }
    };

    request.send();
}

function addAudioPlayer(blob){
    var url = URL.createObjectURL(blob);
    var log = document.getElementById('log');

    var audio = document.querySelector('#replay');
    if (audio != null) {audio.parentNode.removeChild(audio);}

    audio = document.createElement('audio');
    audio.setAttribute('id','replay');
    audio.setAttribute('controls','controls');

    var source = document.createElement('source');
    source.src = url;

    audio.appendChild(source);
    log.parentNode.insertBefore(audio, log);
}

var key = "fd25e622f61b4041810bd67b4d46fad5";

// Speaker Recognition API profile configuration
var Profile = class { constructor (name, profileId) { this.name = name; this.profileId = profileId;}};
var VerificationProfile = class { constructor (name, profileId) { this.name = name; this.profileId = profileId; this.remainingEnrollments = 3}};
var profileIds = [];
var verificationProfile = new VerificationProfile();

// Helper functions - found on SO: really easy way to dump the console logs to the page
(function () {
    var old = console.log;
    var logger = document.getElementById('log');
    var isScrolledToBottom = logger.scrollHeight - logger.clientHeight <= logger.scrollTop + 1;

    console.log = function () {
        for (var i = 0; i < arguments.length; i++) {
            if (typeof arguments[i] == 'object') {
                logger.innerHTML += (JSON && JSON.stringify ? JSON.stringify(arguments[i], undefined, 2) : arguments[i]) + '<br />';
            } else {
                logger.innerHTML += arguments[i] + '<br />';
            }
            if(isScrolledToBottom) logger.scrollTop = logger.scrollHeight - logger.clientHeight;
        }
        old(...arguments);
    }
    console.error = console.log;
})();
