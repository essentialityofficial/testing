let altumcodestart=()=>{let t=document.querySelector(`script[src$="pixel/${pixel_key}"]`),e=is_do_not_track();!e||e&&t.dataset.ignoreDnt?new AltumCodeEvents:e&&console.log(`${pixel_url_base}: ${pixel_key_dnt_message}`)},altumcodeprestart=()=>{altumcodestart()};"complete"!==document.readyState&&("loading"===document.readyState||document.documentElement.doScroll)?document.addEventListener("DOMContentLoaded",()=>{altumcodeprestart()}):altumcodeprestart();
