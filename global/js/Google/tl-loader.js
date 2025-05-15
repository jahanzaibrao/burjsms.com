if (window['google'] != undefined && window['google']['loader'] != undefined) {
    if (!window['google']['elements']) {
        window['google']['elements'] = {};
        google.elements.Version = '1.0';
        google.elements.JSHash = '7ded0ef8ee68924d96a6f6b19df266a8';
        google.elements.LoadArgs = 'file\x3delements\x26v\x3d1\x26packages\x3dtransliteration\x26test\x3d1';
    }
    google.loader.writeLoadTag("css", app_url + "global/js/Google/transliteration.css", false);
    google.loader.writeLoadTag("script", app_url + "global/js/Google/transliteration.I.js", false);
}