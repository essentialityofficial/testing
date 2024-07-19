/* Helpers */
let is_do_not_track = () => {
    if(window.doNotTrack || navigator.doNotTrack || navigator.msDoNotTrack) {

        return window.doNotTrack == "1" || navigator.doNotTrack == "yes" || navigator.doNotTrack == "1" || navigator.msDoNotTrack == "1";

    } else {
        return false;
    }
};

let get_current_url_domain_no_www = () => {
    let url = window.location.href.replace(window.location.protocol + '//', '');

    /* Remove www. from the host */
    if(url.startsWith('www.')) {
        url = url.replace('www.', '');
    }

    return url;
}
