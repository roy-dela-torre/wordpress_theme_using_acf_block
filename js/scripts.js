/* Pass var from PHP - includes/wp_store_locator_setting -> wpsl_php_to_js_enqueue() */
/* Data structure: 
  php_vars = [
    { "id": 12, "zip": "85364", "state": "Arizona" },
    ....
  ];
*/
// Check & wait if a DOM element is loaded and ready
function waitForElement(selector) {
  return new Promise((resolve) => {
      const element = document.querySelector(selector);
      if (element) {
          resolve(element);
      } else {
          const observer = new MutationObserver((mutationsList, observer) => {
              const element = document.querySelector(selector);
              if (element) {
                  resolve(element);
                  observer.disconnect();
              }
          });
          observer.observe(document.body, { childList: true, subtree: true });
      }
  });
}

/* When State is selected in dropdown, put zip code to hidden search field */
(async function() {
  const locations = php_vars;
  let wpslDropdown ='.wpsl-dropdown div ul li';

  const element = await waitForElement(wpslDropdown);
  
  // ready
  if (element) {
    const wpslSearchInput = document.getElementById('wpsl-search-input');
    const wpslDropdown = document.querySelectorAll('.wpsl-dropdown div ul li');

    wpslDropdown.forEach(li => {
      li.addEventListener('click', function(event) {

        let selectedStateId = this.getAttribute('data-value');
        removeProgramFilters();
        removeServiceFilters();

        locations.some(location => {
          if (location.id == selectedStateId) {
            wpslSearchInput.value = location.zip;
            return;
          }
          return false;
        });

        if (selectedStateId != 0) {
          Promise.all([
            populateProgramFilters(selectedStateId),
            populateServiceFilters(selectedStateId)
          ]).then(
            listenForFilterClicks()
          )
        } 

        document.querySelector("#wpsl-search-btn").click();

      });
    });
  }
})();

/* Add default value of ' ' in Search Field */
(function() {
  document.addEventListener('DOMContentLoaded', function(e) {
    const wpslSearchInput = document.getElementById('wpsl-search-input');

    if (wpslSearchInput) {
      wpslSearchInput.placeholder = 'Search by ZIP Code';
      wpslSearchInput.value = ' ';
    }
  });
})();


function listenForFilterClicks(selector) {
  document.querySelectorAll(selector).forEach((el) => {
    el.addEventListener('change', () => {
      document.querySelector("#wpsl-search-btn").click();
    });
  })
}

function populateProgramFilters(stateId) {

  fetch('/wp-json/custom-wpsl/program-filters/?state_id=' + stateId)
      .then(response => response.json())
      .then(data => {
        document.querySelector('#program-filter').innerHTML = data;
        listenForFilterClicks("#program-filter input")
      })
      .catch(error => {
          console.error('Error fetching program filters:', error);
      });
  

}

function populateServiceFilters(stateId) {

    fetch('/wp-json/custom-wpsl/service-filters/?state_id=' + stateId)
      .then(response => response.json())
      .then(data => {
        document.querySelector('#service-filter').innerHTML = data;
        listenForFilterClicks("#service-filter input")
      })
      .catch(error => {
          console.error('Error fetching service filters:', error);
      });

}

function removeProgramFilters() {
  document.querySelector('#program-filter').innerHTML = "";
}

function removeServiceFilters() {
  document.querySelector('#service-filter').innerHTML = "";
}

function addMapScrollGuard(selector) {
	let mapContainer = document.querySelector(selector);
	let mapScrollGuard = document.createElement("div");
	mapScrollGuard.classList.add("scroll-guard")
	mapContainer.appendChild(mapScrollGuard);
	
	
}

function addMapScrollGuardListeners() {
	let mapContainer = document.querySelector("#wpsl-gmap");
	let mapScrollGuard = document.querySelector(".scroll-guard");
	mapScrollGuard.addEventListener('click', () => mapScrollGuard.classList.add("scroll-guard-hidden"));
	mapContainer.addEventListener('mouseleave', () => mapScrollGuard.classList.remove("scroll-guard-hidden"));
}


waitForElement("#program-filter").then(() => listenForFilterClicks("#program-filter input"));
waitForElement("#service-filter").then(() => listenForFilterClicks("#service-filter input"));
waitForElement("#wpsl-gmap iframe").then(() => addMapScrollGuard("#wpsl-gmap"));
waitForElement(".scroll-guard").then(() => addMapScrollGuardListeners());