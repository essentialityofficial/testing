/* Start function */
let altumcodestart = async () => {

    let this_script = document.querySelector(`script[src$="pixel/${pixel_key}"]`);

    /* Check for DNT */
    let is_dnt = is_do_not_track();

    /* Check for manual opt out */
    let optout = is_optout();

    /* Check if we should supress the DNT or not */
    let should_track = !optout && (!is_dnt || (is_dnt && this_script.dataset.ignoreDnt));

    if(should_track) {
        /* Initiate the Visitor */
        let altumcode_visitor = new AltumCodeVisitor();

        await altumcode_visitor.initiate();

        /* Initiate the Events */
        let altumcode_events = new AltumCodeEvents();

        await altumcode_events.initiate();

    } else {

        if(is_dnt) {
            console.log(`${pixel_url_base}: ${pixel_key_dnt_message}`);
        }

        if(optout) {
            console.log(`${pixel_url_base}: ${pixel_key_optout_message}`);
        }
    }
};

let altumcodeprestart = () => {
    // let pushState;
    // let history = window.history;
    //
    // if(history.pushState) {
    //     pushState = history.pushState;
    //
    //     history.pushState = () => {
    //         pushState.apply(this, arguments);
    //         altumcodestart();
    //     };
    //
    //     window.addEventListener('popstate', altumcodestart);
    // }

    altumcodestart();
}

/* Make sure the page is fully loaded before initiating */
if(document.readyState === 'complete' || (document.readyState !== 'loading' && !document.documentElement.doScroll)) {
    altumcodeprestart();
} else {
    document.addEventListener('DOMContentLoaded', () => {
        altumcodeprestart();
    });
}



