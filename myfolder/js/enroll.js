// Comprehensive Philippine Postal Codes Database
        const postalCodes = {
            // NCR - Metro Manila
            "Manila": "1000", "Quiapo": "1001", "Intramuros": "1002", "Santa Cruz": "1003",
            "Malate": "1004", "Pandacan": "1005", "Santa Mesa": "1016", "Paco": "1007",
            "San Miguel": "1008", "Sampaloc": "1008", "Binondo": "1006", "Ermita": "1000",
            "Quezon City": "1100", "Diliman": "1101", "Cubao": "1109", "Project 4": "1109",
            "Project 6": "1100", "Commonwealth": "1121", "Fairview": "1118", "Novaliches": "1116",
            "Caloocan": "1400", "Malabon": "1470", "Navotas": "1485", "Valenzuela": "1440",
            "San Juan": "1500", "Mandaluyong": "1550", "Makati": "1200", "Pasay": "1300",
            "Parañaque": "1700", "Las Piñas": "1740", "Muntinlupa": "1770", "Taguig": "1630",
            "Pateros": "1620", "Pasig": "1600", "Marikina": "1800",
            
            // REGION I - Ilocos Region
            "Laoag": "2900", "Batac": "2906", "San Nicolas": "2901", "Paoay": "2902",
            "Vigan": "2700", "Candon": "2710", "Bantay": "2727", "Santa": "2713",
            "Alaminos": "2404", "Dagupan": "2400", "San Carlos": "2420", "Urdaneta": "2428",
            "Lingayen": "2401", "Binalonan": "2436", "Pozorrubio": "2435",
            "San Fernando (La Union)": "2500", "Bauang": "2501", "Naguilian": "2511",
            
            // REGION II - Cagayan Valley
            "Tuguegarao": "3500", "Aparri": "3515", "Sanchez Mira": "3517", "Buguey": "3516",
            "Cauayan": "3305", "Ilagan": "3300", "Santiago": "3311", "San Mateo": "3318",
            "Bayombong": "3700", "Solano": "3709", "Bambang": "3702",
            
            // REGION III - Central Luzon
            "San Fernando (Pampanga)": "2000", "Angeles": "2009", "Mabalacat": "2010",
            "Guagua": "2003", "Porac": "2008", "Mexico": "2021", "Apalit": "2016",
            "Balanga": "2100", "Dinalupihan": "2110", "Hermosa": "2111", "Limay": "2103",
            "Malolos": "3000", "Meycauayan": "3020", "San Jose del Monte": "3023",
            "Marilao": "3019", "Bocaue": "3018", "Balagtas": "3016", "Bulacan": "3017",
            "Tarlac City": "2300", "Concepcion": "2316", "Capas": "2333", "Camiling": "2306",
            "Cabanatuan": "3100", "Gapan": "3105", "San Jose City": "3121", "Palayan": "3132",
            "Olongapo": "2200", "Subic": "2209", "Castillejos": "2208",
            "San Antonio": "2206", "Botolan": "2202",
            
            // REGION IV-A - CALABARZON
            "Calamba": "4027", "Santa Rosa": "4026", "Biñan": "4024", "San Pedro": "4023",
            "Cabuyao": "4025", "Los Baños": "4030", "Bay": "4033", "Calauan": "4012",
            "San Pablo": "4000", "Alaminos": "4001", "Liliw": "4004",
            "Lucena": "4301", "Tayabas": "4327", "Sariaya": "4322", "Candelaria": "4323",
            "Tiaong": "4325", "Pagbilao": "4302", "Lucban": "4328",
            "Batangas City": "4200", "Lipa": "4217", "Tanauan": "4232", "Santo Tomas": "4234",
            "Lemery": "4209", "Rosario": "4225", "Balayan": "4213", "Nasugbu": "4231",
            "Bacoor": "4102", "Imus": "4103", "Dasmariñas": "4114", "Cavite City": "4100",
            "Tagaytay": "4120", "Trece Martires": "4109", "General Trias": "4107",
            "Silang": "4118", "Indang": "4122", "Naic": "4110", "Maragondon": "4111",
            "Carmona": "4116", "Alfonso": "4123", "Mendez": "4124", "Amadeo": "4119",
            "Antipolo": "1870", "Cainta": "1900", "Taytay": "1920", "Binangonan": "1940",
            "San Mateo": "1850", "Rodriguez": "1860", "Teresa": "1880", "Morong": "1960",
            "Tanay": "1980", "Angono": "1930", "Baras": "1970", "Cardona": "1950",
            "Pililla": "1910", "Jalajala": "1990",
            
            // REGION IV-B - MIMAROPA
            "Puerto Princesa": "5300", "Roxas": "5308", "Taytay": "5312", "Coron": "5316",
            "San Jose": "5100", "Mamburao": "5106", "Sablayan": "5104",
            "Calapan": "5200", "Bongabong": "5211", "Pinamalayan": "5208", "Pola": "5214",
            "Boac": "4900", "Mogpog": "4901", "Gasan": "4905", "Santa Cruz": "4902",
            "Romblon": "5500", "San Jose": "5504", "Odiongan": "5505",
            
            // REGION V - Bicol
            "Legazpi": "4500", "Tabaco": "4511", "Ligao": "4504", "Daraga": "4501",
            "Naga": "4400", "Iriga": "4431", "Pili": "4418", "Nabua": "4434",
            "Daet": "4600", "Mercedes": "4601", "Jose Panganiban": "4606",
            "Sorsogon City": "4700", "Bulan": "4706", "Irosin": "4707",
            "Masbate City": "5400", "Placer": "5408", "Cataingan": "5405",
            "Virac": "4800", "San Andres": "4806", "Bato": "4807",
            
            // REGION VI - Western Visayas
            "Iloilo City": "5000", "Passi": "5037", "Pototan": "5008", "Barotac Nuevo": "5007",
            "Bacolod": "6100", "Silay": "6116", "Talisay": "6115", "Bago": "6101",
            "Victorias": "6119", "San Carlos": "6127", "Cadiz": "6121",
            "Roxas": "5800", "Panitan": "5812", "Panay": "5801",
            "Kalibo": "5600", "Ibajay": "5615", "Nabas": "5607",
            "San Jose de Buenavista": "5700", "Pandan": "5712", "Sebaste": "5716",
            
            // REGION VII - Central Visayas
            "Cebu City": "6000", "Mandaue": "6014", "Lapu-Lapu": "6015", "Talisay": "6045",
            "Danao": "6004", "Toledo": "6038", "Carcar": "6019", "Naga": "6037",
            "Minglanilla": "6046", "Consolacion": "6001",
            "Tagbilaran": "6300", "Ubay": "6315", "Jagna": "6308", "Talibon": "6320",
            "Dumaguete": "6200", "Bais": "6206", "Canlaon": "6223", "Tanjay": "6204",
            "Mandaue": "6014", "Lapu-Lapu": "6015", "Minglanilla": "6046",
            
            // REGION VIII - Eastern Visayas
            "Tacloban": "6500", "Ormoc": "6541", "Baybay": "6521", "Palo": "6501",
            "Calbayog": "6710", "Catbalogan": "6700", "Borongan": "6800",
            "Naval": "6543", "Almeria": "6542", "Caibiran": "6544",
            "Maasin": "6600", "Sogod": "6606", "Hinunangan": "6626",
            
            // REGION IX - Zamboanga Peninsula
            "Zamboanga City": "7000", "Pagadian": "7016", "Dipolog": "7100",
            "Dapitan": "7101", "Ipil": "7001", "Sindangan": "7112",
            
            // REGION X - Northern Mindanao
            "Cagayan de Oro": "9000", "Gingoog": "9014", "Valencia": "8709",
            "Malaybalay": "8700", "Maramag": "8714", "Iligan": "9200",
            "Ozamiz": "7200", "Oroquieta": "7207", "Tangub": "7214",
            
            // REGION XI - Davao Region
            "Davao City": "8000", "Tagum": "8100", "Panabo": "8105", "Digos": "8002",
            "Mati": "8200", "Mabini": "8207",
            
            // REGION XII - SOCCSKSARGEN
            "General Santos": "9500", "Koronadal": "9506", "Tacurong": "9800",
            "Kidapawan": "9400", "Cotabato City": "9600",
            
            // REGION XIII - Caraga
            "Butuan": "8600", "Cabadbaran": "8605", "Bayugan": "8502",
            "Surigao City": "8400", "Tandag": "8300",
            
            // CAR - Cordillera Administrative Region
            "Baguio": "2600", "La Trinidad": "2601", "Bontoc": "2616", "Tabuk": "3800",
            "Bangued": "2800", "Lagawe": "3600",
            
            // BARMM - Bangsamoro Autonomous Region
            "Marawi": "9700", "Lamitan": "7316", "Jolo": "7400", "Bongao": "7500"
        };

        // Generate past 10 school years for dropdown
        const schoolYearSelect = document.getElementById('last_school_year');
        const currentYear = new Date().getFullYear();
        
        for (let i = 0; i < 10; i++) {
            const startYear = currentYear - i - 1;
            const endYear = startYear + 1;
            const schoolYear = `${startYear}-${endYear}`;
            const option = document.createElement('option');
            option.value = schoolYear;
            option.textContent = schoolYear;
            schoolYearSelect.appendChild(option);
        }

        // PSGC API Integration for Address Dropdowns
        const regionSelect = document.getElementById('region');
        const provinceSelect = document.getElementById('province');
        const citySelect = document.getElementById('city');
        const barangaySelect = document.getElementById('barangay');
        const zipcodeInput = document.getElementById('zipcode');
        const streetInput = document.getElementById('street');
        const addressHidden = document.getElementById('address');

        // Load regions on page load
        async function loadRegions() {
            try {
                const response = await fetch('https://psgc.gitlab.io/api/regions/');
                const regions = await response.json();
                
                regionSelect.innerHTML = '<option value="">-- Select Region --</option>';
                regions.forEach(region => {
                    const option = document.createElement('option');
                    option.value = region.code;
                    option.textContent = region.name;
                    option.dataset.name = region.name;
                    regionSelect.appendChild(option);
                });
            } catch (error) {
                console.error('Error loading regions:', error);
                alert('Failed to load regions. Please refresh the page.');
            }
        }

        // Load provinces based on selected region
        regionSelect.addEventListener('change', async function() {
            const regionCode = this.value;
            const regionName = this.options[this.selectedIndex]?.dataset.name || '';
            
            provinceSelect.innerHTML = '<option value="">-- Loading... --</option>';
            citySelect.innerHTML = '<option value="">-- Select Province First --</option>';
            barangaySelect.innerHTML = '<option value="">-- Select City First --</option>';
            zipcodeInput.value = '';
            
            provinceSelect.disabled = true;
            citySelect.disabled = true;
            barangaySelect.disabled = true;
            
            provinceSelect.classList.add('bg-gray-100');
            citySelect.classList.add('bg-gray-100');
            barangaySelect.classList.add('bg-gray-100');
            
            if (regionCode) {
                try {
                    const response = await fetch(`https://psgc.gitlab.io/api/regions/${regionCode}/provinces/`);
                    const provinces = await response.json();
                    
                    provinceSelect.innerHTML = '<option value="">-- Select Province --</option>';
                    provinces.forEach(province => {
                        const option = document.createElement('option');
                        option.value = province.code;
                        option.textContent = province.name;
                        option.dataset.name = province.name;
                        provinceSelect.appendChild(option);
                    });
                    
                    provinceSelect.disabled = false;
                    provinceSelect.classList.remove('bg-gray-100');
                } catch (error) {
                    console.error('Error loading provinces:', error);
                    provinceSelect.innerHTML = '<option value="">-- Error loading provinces --</option>';
                }
            }
            
            updateAddress();
        });

        // Load cities based on selected province
        provinceSelect.addEventListener('change', async function() {
            const provinceCode = this.value;
            const provinceName = this.options[this.selectedIndex]?.dataset.name || '';
            
            citySelect.innerHTML = '<option value="">-- Loading... --</option>';
            barangaySelect.innerHTML = '<option value="">-- Select City First --</option>';
            zipcodeInput.value = '';
            
            citySelect.disabled = true;
            barangaySelect.disabled = true;
            
            citySelect.classList.add('bg-gray-100');
            barangaySelect.classList.add('bg-gray-100');
            
            if (provinceCode) {
                try {
                    const response = await fetch(`https://psgc.gitlab.io/api/provinces/${provinceCode}/cities-municipalities/`);
                    const cities = await response.json();
                    
                    citySelect.innerHTML = '<option value="">-- Select City/Municipality --</option>';
                    cities.forEach(city => {
                        const option = document.createElement('option');
                        option.value = city.code;
                        option.textContent = city.name;
                        option.dataset.name = city.name;
                        citySelect.appendChild(option);
                    });
                    
                    citySelect.disabled = false;
                    citySelect.classList.remove('bg-gray-100');
                } catch (error) {
                    console.error('Error loading cities:', error);
                    citySelect.innerHTML = '<option value="">-- Error loading cities --</option>';
                }
            }
            
            updateAddress();
        });

        // Load barangays based on selected city AND auto-fill zip code
        citySelect.addEventListener('change', async function() {
            const cityCode = this.value;
            const cityName = this.options[this.selectedIndex]?.dataset.name || '';
            
            barangaySelect.innerHTML = '<option value="">-- Loading... --</option>';
            barangaySelect.disabled = true;
            barangaySelect.classList.add('bg-gray-100');
            
            // Auto-fill zip code based on city name
            if (cityName && postalCodes[cityName]) {
                zipcodeInput.value = postalCodes[cityName];
                zipcodeInput.classList.add('bg-green-50', 'border-green-500');
            } else {
                zipcodeInput.value = '';
                zipcodeInput.classList.remove('bg-green-50', 'border-green-500');
            }
            
            if (cityCode) {
                try {
                    const response = await fetch(`https://psgc.gitlab.io/api/cities-municipalities/${cityCode}/barangays/`);
                    const barangays = await response.json();
                    
                    barangaySelect.innerHTML = '<option value="">-- Select Barangay --</option>';
                    barangays.forEach(barangay => {
                        const option = document.createElement('option');
                        option.value = barangay.code;
                        option.textContent = barangay.name;
                        option.dataset.name = barangay.name;
                        barangaySelect.appendChild(option);
                    });
                    
                    barangaySelect.disabled = false;
                    barangaySelect.classList.remove('bg-gray-100');
                } catch (error) {
                    console.error('Error loading barangays:', error);
                    barangaySelect.innerHTML = '<option value="">-- Error loading barangays --</option>';
                }
            }
            
            updateAddress();
        });

        barangaySelect.addEventListener('change', updateAddress);
        streetInput.addEventListener('input', updateAddress);

        function updateAddress() {
            const parts = [];
            
            if (streetInput.value) parts.push(streetInput.value);
            
            const barangayName = barangaySelect.options[barangaySelect.selectedIndex]?.dataset.name;
            if (barangayName) parts.push(barangayName);
            
            const cityName = citySelect.options[citySelect.selectedIndex]?.dataset.name;
            if (cityName) parts.push(cityName);
            
            const provinceName = provinceSelect.options[provinceSelect.selectedIndex]?.dataset.name;
            if (provinceName) parts.push(provinceName);
            
            const regionName = regionSelect.options[regionSelect.selectedIndex]?.dataset.name;
            if (regionName) parts.push(regionName);
            
            if (zipcodeInput.value) parts.push(zipcodeInput.value);
            
            addressHidden.value = parts.join(', ');
        }

        // Initialize regions on page load
        document.addEventListener('DOMContentLoaded', function() {
            loadRegions();
        });

        // Birthdate validation - TC-008 (Future Date - CRITICAL)
        const birthdateInput = document.getElementById('birthdate');
        const birthdateError = document.getElementById('birthdateError');
        const ageInput = document.getElementById('age');

        birthdateInput.addEventListener('change', function() {
            const selectedDate = new Date(this.value);
            const today = new Date();
            today.setHours(0, 0, 0, 0);
            
            // TC-008: Check for future date
            if (selectedDate > today) {
                this.classList.add('border-red-500');
                this.classList.remove('border-gray-300', 'border-green-500');
                birthdateError.classList.remove('hidden');
                ageInput.value = '';
                return;
            }
            
            // Valid date - remove error styling
            this.classList.remove('border-red-500');
            this.classList.add('border-green-500');
            birthdateError.classList.add('hidden');
            
            // Calculate age (TC-010)
            let age = today.getFullYear() - selectedDate.getFullYear();
            const monthDiff = today.getMonth() - selectedDate.getMonth();
            
            if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < selectedDate.getDate())) {
                age--;
            }
            
            ageInput.value = age >= 0 ? age : '0';
        });

        // Contact number validation - TC-015, TC-016, TC-017 (CRITICAL)
        const contactInput = document.getElementById('contact_no');
        const contactError = document.getElementById('contactError');

        contactInput.addEventListener('input', function() {
            const value = this.value;
            
            // TC-015 & TC-017: Check for alphabetic characters in real-time
            if (/[a-zA-Z]/.test(value)) {
                this.classList.add('border-red-500');
                this.classList.remove('border-gray-300', 'border-green-500');
                contactError.classList.remove('hidden');
                return;
            }
            
            // TC-016: Accept special characters (dashes, spaces) but remove them
            const cleanValue = value.replace(/[^0-9]/g, '');
            
            // Update input with clean value
            if (cleanValue !== value) {
                this.value = cleanValue;
            }
            
            // Remove error if no letters
            contactError.classList.add('hidden');
            
            // Limit to 11 digits
            if (this.value.length > 11) {
                this.value = this.value.slice(0, 11);
            }
            
            // TC-014: Validate format (11 digits starting with 09)
            if (this.value.length === 11 && this.value.startsWith('09')) {
                this.classList.remove('border-red-500', 'border-gray-300');
                this.classList.add('border-green-500');
            } else if (this.value.length > 0) {
                this.classList.remove('border-green-500');
                this.classList.add('border-gray-300');
            } else {
                this.classList.remove('border-red-500', 'border-green-500');
                this.classList.add('border-gray-300');
            }
        });

        // Form validation before submit - TC-023
        document.querySelector('form').addEventListener('submit', function(e) {
            const birthdate = new Date(birthdateInput.value);
            const today = new Date();
            today.setHours(0, 0, 0, 0);
            
            // Validate birthdate is not in future
            if (birthdate > today) {
                e.preventDefault();
                alert('Birthdate cannot be in the future. Please select a valid date.');
                birthdateInput.focus();
                return false;
            }
            
            const contact = contactInput.value;
            
            // Validate contact has no letters
            if (/[a-zA-Z]/.test(contact)) {
                e.preventDefault();
                alert('Contact number cannot contain alphabetic characters. Please enter numbers only.');
                contactInput.focus();
                return false;
            }
            
            // Validate contact number format
            if (!/^09[0-9]{9}$/.test(contact)) {
                e.preventDefault();
                alert('Please enter a valid Philippine mobile number (09XXXXXXXXX - 11 digits starting with 09).');
                contactInput.focus();
                return false;
            }
            
            // Validate address is complete
            if (!addressHidden.value || !regionSelect.value || !provinceSelect.value || !citySelect.value || !barangaySelect.value) {
                e.preventDefault();
                alert('Please complete all address fields (Region, Province, City, Barangay).');
                regionSelect.focus();
                return false;
            }
            
            return true;
        });

    function toggleRequirements() {
    const content = document.getElementById('requirementsContent');
    const arrow = document.getElementById('requirementsArrow');
    
    if (content.classList.contains('hidden')) {
        content.classList.remove('hidden');
        arrow.style.transform = 'rotate(180deg)';
    } else {
        content.classList.add('hidden');
        arrow.style.transform = 'rotate(0deg)';
    }
}