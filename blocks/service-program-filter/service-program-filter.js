function initServiceProgramFilter() {

    let dropdown = document.querySelector("#service-program-states");
    let post_type = document.querySelector("#post-type").value;

    if (dropdown) {
        dropdown.addEventListener('change', (e) => {
            let state = e.target.value;

            fetch('/wp-json/custom-clarvida/service-program-cards/?state=' + state + "&post_type=" + post_type)
                .then(response => response.json())
                .then(data => {
                    document.querySelector('#lp-service-program-filter__grid').innerHTML = data;
                })
                .catch(error => {
                    console.error('Error fetching cards:', error);
                });

        });
    }


}

document.addEventListener('DOMContentLoaded', initServiceProgramFilter);
document.addEventListener('serviceProgramFilterBlockLoaded', initServiceProgramFilter);
