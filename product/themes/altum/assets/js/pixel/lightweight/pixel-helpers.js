let is_do_not_track=()=>(!!window.doNotTrack||!!navigator.doNotTrack||!!navigator.msDoNotTrack)&&("1"==window.doNotTrack||"yes"==navigator.doNotTrack||"1"==navigator.doNotTrack||"1"==navigator.msDoNotTrack),get_current_url_domain_no_www=()=>{let o=window.location.href.replace(window.location.protocol+"//","");return o.startsWith("www.")&&(o=o.replace("www.","")),o};
