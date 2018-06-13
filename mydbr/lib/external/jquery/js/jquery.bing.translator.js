(function () {
    'use strict';
    var global = window,
        root,
        baseUrl = 'http://api.microsofttranslator.com/V2/Ajax.svc/',
        dataType = 'jsonp',
        jsonp = 'oncomplete';

    if (!global.Microsoft) {
        root = global.Microsoft = {};
    } else {
        root = global.Microsoft;
    }

    root.Translator = function (appId, serviceUrl) {
        var _appId = appId,
            _serviceUrl = typeof (serviceUrl) === 'undefined' ?
                baseUrl :
                serviceUrl;

        function _invokeService(methodName, data) {
            if (typeof (data) === 'undefined') {
                data = {};
            }
            data.appId = _appId;
            return jQuery.ajax(
                { url: _serviceUrl + methodName,
                    dataType: dataType,
                    jsonp: jsonp,
                    data: data }
            );
        }

        return {
            addTranslation: function (originalText, translatedText, from, to, options) {
                var data = { originalText: originalText,
                    translatedText: translatedText,
                    from: from,
                    to: to,
                    user: options.user,
                    rating: options.rating,
                    contentType: options.contentType,
                    category: options.category,
                    uri: options.uri };
                return _invokeService('AddTranslation', data);
            },
            addTranslationArray: function (translations, from, to, options) {
                var translationValues = JSON.stringify(translations),
                    optionvalues = JSON.stringify(options),
                    data = { translations: translationValues,
                        from: from,
                        to: to,
                        options: optionvalues };

                return _invokeService('AddTranslationArray', data);
            },
            breakSentences: function (text, language) {
                var data = { text: text, language: language };
                return _invokeService('BreakSentences', data);
            },
            detect: function (text) {
                return _invokeService('Detect', { text: text });
            },
            detectArray: function (texts) {
                var textValues = JSON.stringify(texts);
                return _invokeService('DetectArray', { texts: textValues });
            },
            getLanguageNames: function (locale, languageCodes) {
                var languageCodeValues = JSON.stringify(languageCodes),
                    data = { locale: locale, languageCodes: languageCodeValues };
                return _invokeService('GetLanguageNames', data);
            },
            getLanguagesForSpeak: function () {
                return _invokeService('GetLanguagesForSpeak');
            },
            getLanguagesForTranslate: function () {
                return _invokeService('GetLanguagesForTranslate');
            },
            getTranslations: function (text, from, to, maxTranslations, options) {
                var optionvalues = JSON.stringify(options),
                    data = { text: text,
                        from: from,
                        to: to,
                        maxTranslations: maxTranslations,
                        options: optionvalues };
                return _invokeService('GetTranslations', data);
            },
            getTranslationsArray: function (texts, from, to, maxTranslations, options) {
                var textValues = JSON.stringify(texts),
                    optionvalues = JSON.stringify(options),
                    data = { texts: textValues,
                        from: from,
                        to: to,
                        maxTranslations: maxTranslations,
                        options: optionvalues };
                return _invokeService('GetTranslationsArray', data);
            },
            speak: function (text, language, format) {
                if (typeof (format) === 'undefined') {
                    format = 'audio/wav';
                }
                var data = { text: text, language: language, format: format };
                return _invokeService('Speak', data);
            },
            translate: function (text, from, to) {
                var data = { text: text, from: from, to: to };
                return _invokeService('Translate', data);
            },
            translateArray: function (texts, from, to, options) {
                var textValues = JSON.stringify(texts),
                    optionvalues = JSON.stringify(options),
                    data = { texts: textValues,
                        from: from,
                        to: to,
                        options: optionvalues };
                return _invokeService('TranslateArray', data);
            }
        };
    };

    root.Translator.getAppIdToken = function (appId,
                                              minRatingRead,
                                              minRatingWrite,
                                              expireSeconds, 
                                              serviceUrl) {
        var _serviceUrl = typeof (serviceUrl) === 'undefined' ?
                baseUrl :
                serviceUrl;

        return jQuery.ajax(
            { url: _serviceUrl + 'GetAppIdToken',
                dataType: dataType,
                jsonp: jsonp,
                data: { appId: appId,
                    minRatingRead: minRatingRead,
                    minRatingWrite: minRatingWrite,
                    expireSeconds: expireSeconds }
                }
        );
    };
}());