// Intersection Observer untuk animasi scroll
document.addEventListener('DOMContentLoaded', () => {
    const observer = new IntersectionObserver(entries => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('show');
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.1 });

    // Tunggu sebentar untuk memastikan DOM sudah siap
    setTimeout(() => {
        document.querySelectorAll('.animate-on-scroll').forEach(el => {
            // Cek jika elemen sudah terlihat saat load
            const rect = el.getBoundingClientRect();
            const isVisible = rect.top < window.innerHeight && rect.bottom > 0;
            if (isVisible) {
                el.classList.add('show');
            } else {
                observer.observe(el);
            }
        });
    }, 100);
});

// ======================
// Session flash messages
// ======================
document.addEventListener('DOMContentLoaded', function () {
    // Success message
    const successMessage = document.querySelector('[data-success-message]');
    if (successMessage) {
        Swal.fire({
            icon: "success",
            title: "Berhasil",
            text: successMessage.dataset.successMessage,
            confirmButtonText: "OK",
            showConfirmButton: true,
            timer: null
        });
    }

    // Error message
    const errorMessage = document.querySelector('[data-error-message]');
    if (errorMessage) {
        Swal.fire({
            icon: "error",
            title: "Gagal",
            text: errorMessage.dataset.errorMessage,
            confirmButtonText: "OK",
            showConfirmButton: true,
            timer: null
        });
    }
});

document.addEventListener("DOMContentLoaded", () => {
    // ======================
    // Toggle menu hamburger
    // ======================
    const toggle = document.getElementById("menu-toggle");
    const menu = document.getElementById("menu");
    if (toggle && menu) {
        toggle.addEventListener("click", () => menu.classList.toggle("hidden"));
    }

    // ======================
    // Navbar scroll effect
    // ======================
    const navbar = document.getElementById("navbar");
    if (navbar) {
        window.addEventListener("scroll", () => {
            if (window.scrollY > 50) {
                navbar.classList.add("bg-opacity-80", "backdrop-blur-md");
            } else {
                navbar.classList.remove("bg-opacity-80", "backdrop-blur-md");
            }
        });
    }

    // ======================
    // Greeting berdasarkan jam
    // ======================
    const updateGreeting = () => {
        const greetingEl = document.getElementById("greeting");
        const iconEl = document.getElementById("greeting-icon");
        if (greetingEl && iconEl) {
            const hour = new Date().getHours();
            let greeting = "", icon = "";
            if (hour >= 5 && hour < 11) { greeting = "Selamat Pagi"; icon = "☀️"; }
            else if (hour >= 11 && hour < 15) { greeting = "Selamat Siang"; icon = "🌤️"; }
            else if (hour >= 15 && hour < 18) { greeting = "Selamat Sore"; icon = "🌇"; }
            else { greeting = "Selamat Malam"; icon = "🌙"; }
            greetingEl.textContent = greeting;
            iconEl.textContent = icon;
        }
    };
    updateGreeting();
    setInterval(updateGreeting, 60000);

    // ======================
    // Dropdown user dengan fade
    // ======================
    const userMenuToggle = document.getElementById("greeting-icon");
    const userDropdown = document.getElementById("user-dropdown");
    if (userMenuToggle && userDropdown) {
        const showDropdown = () => {
            userDropdown.classList.remove("hidden");
            setTimeout(() => userDropdown.classList.add("opacity-100"), 10);
        }
        const hideDropdown = () => {
            userDropdown.classList.remove("opacity-100");
            setTimeout(() => userDropdown.classList.add("hidden"), 200);
        }
        userMenuToggle.addEventListener("click", () => {
            if (userDropdown.classList.contains("hidden")) showDropdown();
            else hideDropdown();
        });
        document.addEventListener("click", (e) => {
            if (!userMenuToggle.contains(e.target) && !userDropdown.contains(e.target)) hideDropdown();
        });
    }

    // ======================
    // Logout confirm
    // ======================
    const logout = (btnId, formId) => {
        const btn = document.getElementById(btnId);
        const form = document.getElementById(formId);
        if (btn && form) {
            btn.addEventListener("click", () => {
                Swal.fire({
                    title: "Apakah Anda yakin?",
                    text: "Anda akan keluar dari akun ini",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Iya, keluar",
                    cancelButtonText: "Tidak",
                }).then((result) => {
                    if (result.isConfirmed) form.submit();
                });
            });
        }
    }
    logout("logout-btn", "logout-form");
    logout("logout-btn-mobile", "logout-form-mobile");

    // ======================
    // Toggle revenue visibility
    // ======================
    function setupToggle(toggleId, amountId, eyeIconId) {
        const toggleBtn = document.getElementById(toggleId);
        const amountEl = document.getElementById(amountId);
        const eyeIcon = document.getElementById(eyeIconId);
        if (!toggleBtn || !amountEl || !eyeIcon) return;
        const actualAmount = amountEl.textContent;
        const maskedAmount = 'Rp ******';

        toggleBtn.addEventListener('click', () => {
            if (amountEl.textContent === actualAmount) {
                amountEl.textContent = maskedAmount;
                eyeIcon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.956 9.956 0 012.223-3.393m1.77-1.77A9.956 9.956 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.06 10.06 0 01-4.132 5.411M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3l18 18" />
                `;
            } else {
                amountEl.textContent = actualAmount;
                eyeIcon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                `;
            }
        });
    }
    setupToggle('toggle-revenue-visibility', 'revenue-amount', 'eye-icon');
    setupToggle('toggle-total-revenue-visibility', 'total-revenue-amount', 'eye-icon-total');
    setupToggle('toggle-monthly-revenue-visibility', 'monthly-revenue-amount', 'eye-icon-monthly');

    // ======================
    // BOOKING (kursi realtime)
    // ======================
    const jadwalSelect = document.getElementById('jadwal_id');
    const seatCheckboxes = document.querySelectorAll('.seat-checkbox');
    const bookingForm = document.getElementById('booking-form');

    if (jadwalSelect) {
        jadwalSelect.addEventListener('change', function () {
            const jadwalId = this.value;

            if (jadwalId) {
                fetch(`/jadwal/${jadwalId}/seats`)
                    .then(res => res.json())
                    .then(bookedSeats => {
                        seatCheckboxes.forEach(cb => {
                            const seatDiv = cb.nextElementSibling;
                            if (bookedSeats.includes(cb.value)) {
                                cb.disabled = true;
                                cb.checked = false;
                                seatDiv.className =
                                    "w-16 h-16 flex items-center justify-center rounded bg-red-500 text-white cursor-not-allowed";
                            } else {
                                cb.disabled = false;
                                seatDiv.className =
                                    "w-16 h-16 flex items-center justify-center rounded bg-green-500 text-white hover:bg-blue-500";
                            }
                        });
                    });
            } else {
                // reset ke default abu-abu
                seatCheckboxes.forEach(cb => {
                    cb.disabled = true;
                    cb.checked = false;
                    cb.nextElementSibling.className =
                        "w-16 h-16 flex items-center justify-center rounded bg-gray-300 text-black";
                });
            }
        });
    }

    // ======================
    // STEP 3 (pilih kursi + hitung total)
    // ======================
    const selectedSeatsDisplay = document.getElementById('selected-seats');
    const totalPriceDisplay = document.getElementById('total-price');
    const bookButton = document.getElementById('book-button');
    const seatForm = document.getElementById('seat-form');
    const pricePerSeat = seatForm ? parseInt(seatForm.dataset.pricePerSeat) : 0;

    function updateDisplay() {
        const selectedSeats = Array.from(seatCheckboxes)
            .filter(cb => cb.checked && !cb.disabled)
            .map(cb => cb.value);

        // Update kursi terpilih
        if (selectedSeatsDisplay) {
            selectedSeatsDisplay.textContent =
                selectedSeats.length > 0
                    ? selectedSeats.join(', ')
                    : 'Belum ada kursi dipilih';
        }

        // Update total harga
        if (totalPriceDisplay) {
            const totalPrice = selectedSeats.length * pricePerSeat;
            totalPriceDisplay.textContent =
                'Rp ' + totalPrice.toLocaleString('id-ID');
        }

        // Enable/disable tombol pesan
        if (bookButton) {
            bookButton.disabled = selectedSeats.length === 0;
        }

        // Highlight kursi dipilih
        seatCheckboxes.forEach(cb => {
            const seatBox = cb.nextElementSibling;
            if (!cb.disabled) {
                if (cb.checked) {
                    seatBox.classList.add("bg-green-500", "text-white", "border-green-500");
                    seatBox.classList.remove("bg-gray-200", "text-gray-700", "border-gray-300");
                } else {
                    seatBox.classList.add("bg-gray-200", "text-gray-700", "border-gray-300");
                    seatBox.classList.remove("bg-green-500", "text-white", "border-green-500");
                }
            }
        });
    }

    // Event untuk update display
    seatCheckboxes.forEach(cb => {
        cb.addEventListener('change', function () {
            updateDisplay();
            // tambahan highlight ring kuning
            const seatDiv = this.nextElementSibling;
            if (this.checked) {
                seatDiv.classList.add("ring-4", "ring-yellow-400");
            } else {
                seatDiv.classList.remove("ring-4", "ring-yellow-400");
            }
        });
    });

    // Validasi minimal 1 kursi
    if (seatForm) {
        seatForm.addEventListener('submit', function (e) {
            const checked = document.querySelectorAll('.seat-checkbox:checked').length;
            if (checked === 0) {
                e.preventDefault();
                Swal.fire({
                    icon: 'warning',
                    title: 'Peringatan',
                    text: 'Pilih minimal 1 kursi!',
                    confirmButtonText: 'OK'
                });
            }
        });
    }

    // Initial display
    updateDisplay();
});

// ======================
// Page-specific JavaScript
// ======================

// Function to get current page path
function getCurrentPage() {
    return window.location.pathname;
}

// ======================
// Riwayat: Cancel booking confirmation
// ======================
if (getCurrentPage().includes('/riwayat')) {
    const cancelForms = document.querySelectorAll('.cancel-booking-form');
    cancelForms.forEach(form => {
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: 'Yakin ingin membatalkan pesanan ini?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, batalkan!',
                cancelButtonText: 'Tidak'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
}

// Function to check if element exists
function elementExists(selector) {
    return document.querySelector(selector) !== null;
}

// ======================
// Optimize animations for home page
// ======================
if (getCurrentPage() === '/' || getCurrentPage().includes('home')) {
    // Animasi sudah ditangani oleh Intersection Observer
    // Tidak perlu inisialisasi tambahan
}

// ======================
// Booking Step 1: City validation
// ======================
if (getCurrentPage().includes('/booking/step1')) {
    const kotaAwal = document.getElementById('kota_awal');
    const kotaTujuan = document.getElementById('kota_tujuan');

    if (kotaAwal && kotaTujuan) {
        function validateCities() {
            if (kotaAwal.value && kotaTujuan.value && kotaAwal.value === kotaTujuan.value) {
                alert('Kota awal dan tujuan tidak boleh sama');
                kotaTujuan.value = '';
            }
        }

        kotaAwal.addEventListener('change', validateCities);
        kotaTujuan.addEventListener('change', validateCities);
    }
}

// ======================
// Admin Rute Create: Price formatting and form confirmation
// ======================
if (getCurrentPage().includes('/admin/rute/create')) {
    // Function to format number with Indonesian format (dots as thousands separator)
    function formatNumber(num) {
        return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }

    // Function to unformat number (remove dots)
    function unformatNumber(str) {
        return str.replace(/\./g, '');
    }

    const hargaInput = document.getElementById('harga_tiket');
    if (hargaInput) {
        hargaInput.addEventListener('input', function (e) {
            let value = e.target.value;

            // Remove any non-numeric characters except dots
            value = value.replace(/[^\d.]/g, '');

            // Remove existing dots to reformat
            value = unformatNumber(value);

            // Format with dots
            if (value) {
                value = formatNumber(value);
            }

            e.target.value = value;
        });
    }

    const formRute = document.getElementById('formRute');
    if (formRute) {
        formRute.addEventListener('submit', function (e) {
            e.preventDefault(); // stop submit dulu

            // Remove formatting before submit
            if (hargaInput) {
                hargaInput.value = unformatNumber(hargaInput.value);
            }

            Swal.fire({
                title: 'Apakah kamu yakin?',
                text: "Data rute akan ditambahkan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#16a34a',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Iya, simpan!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    e.target.submit();
                }
            });
        });
    }
}

// ======================
// Admin Supir Create: Form confirmation
// ======================
if (getCurrentPage().includes('/admin/supir/create')) {
    const formSupir = document.getElementById('formSupir');
    if (formSupir) {
        formSupir.addEventListener('submit', function (e) {
            e.preventDefault(); // stop submit dulu
            Swal.fire({
                title: 'Apakah kamu yakin?',
                text: "Data supir akan ditambahkan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#16a34a',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Iya, simpan!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    e.target.submit();
                }
            });
        });
    }
}

// ======================
// Admin Mobil Create: Form confirmation
// ======================
if (getCurrentPage().includes('/admin/mobil/create')) {
    const formMobil = document.getElementById('formMobil');
    if (formMobil) {
        formMobil.addEventListener('submit', function (e) {
            e.preventDefault(); // stop submit dulu
            Swal.fire({
                title: 'Apakah kamu yakin?',
                text: "Data mobil akan ditambahkan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#16a34a',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Iya, simpan!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    e.target.submit();
                }
            });
        });
    }
}

// ======================
// Admin Pelanggan Create: Form confirmation
// ======================
if (getCurrentPage().includes('/admin/pelanggan/create')) {
    const formPelanggan = document.getElementById('formPelanggan');
    if (formPelanggan) {
        formPelanggan.addEventListener('submit', function (e) {
            e.preventDefault();
            Swal.fire({
                title: 'Apakah kamu yakin?',
                text: "Data pelanggan akan ditambahkan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#16a34a',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Iya, simpan!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    e.target.submit();
                }
            });
        });
    }
}

// ======================
// Admin Jadwals Create: Form confirmation
// ======================
if (getCurrentPage().includes('/admin/jadwals/create')) {
    const formJadwal = document.getElementById('formJadwal');
    if (formJadwal) {
        formJadwal.addEventListener('submit', function (e) {
            e.preventDefault();
            Swal.fire({
                title: 'Apakah kamu yakin?',
                text: "Jadwal baru akan ditambahkan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#16a34a',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Iya, simpan!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    e.target.submit();
                }
            });
        });
    }
}

// ======================
// Counter animation (optimized for home page only)
// ======================
if (getCurrentPage() === '/' || getCurrentPage().includes('home')) {
    function animateCounter(el) {
        const target = +el.getAttribute("data-target");
        const suffix = el.getAttribute("data-suffix") || "";
        let current = 0;
        const increment = Math.ceil(target / 100);
        const timer = setInterval(() => {
            current += increment;
            if (current >= target) {
                el.textContent = target + suffix;
                clearInterval(timer);
            } else {
                el.textContent = current + suffix;
            }
        }, 30);
    }

    const statsSection = document.getElementById("stats-section");
    if (statsSection) {
        const counters = statsSection.querySelectorAll(".counter");
        let started = false;
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting && !started) {
                    counters.forEach(counter => animateCounter(counter));
                    started = true;
                }
            });
        }, { threshold: 0.3 });
        observer.observe(statsSection);
    }
}

// ======================
// Lazy loading Google Maps
// ======================
if (getCurrentPage() === '/' || getCurrentPage().includes('home')) {
    const mapContainers = document.querySelectorAll('.map-container');
    mapContainers.forEach(container => {
        const placeholder = container.querySelector('.map-placeholder');
        if (placeholder) {
            placeholder.addEventListener('click', function () {
                const mapSrc = container.getAttribute('data-map-src');
                if (mapSrc) {
                    const iframe = document.createElement('iframe');
                    iframe.src = mapSrc;
                    iframe.width = '600';
                    iframe.height = '450';
                    iframe.style.border = '0';
                    iframe.allowFullscreen = true;
                    iframe.loading = 'lazy';
                    iframe.referrerPolicy = 'no-referrer-when-downgrade';

                    container.innerHTML = '';
                    container.appendChild(iframe);
                }
            });
        }
    });
}

// ======================
// BOOKING: Konfirmasi update status di admin
// ======================
// Hanya target form yang memiliki input hidden 'status' (untuk update status booking)
document.querySelectorAll('form[action*="bookings"] input[name="status"]').forEach(input => {
    const form = input.closest('form');
    if (form) {
        form.addEventListener('submit', function (e) {
            e.preventDefault();

            const status = e.submitter.value;
            const statusText = status === 'setuju' ? 'Setuju' : 'Batal';

            // ambil detail booking dari tabel
            const row = this.closest('tr');
            const userName = row.querySelector('td:nth-child(1)').textContent.trim();
            const origin = row.querySelector('td:nth-child(2)').textContent.trim();
            const destination = row.querySelector('td:nth-child(3)').textContent.trim();
            const date = row.querySelector('td:nth-child(4)').textContent.trim();

            let confirmMessage = '';
            let confirmTitle = '';

            if (status === 'setuju') {
                confirmTitle = 'Konfirmasi Persetujuan';
                confirmMessage = `Apakah Anda yakin ingin menyetujui pemesanan untuk:\n\nPelanggan: ${userName}\nTujuan: ${origin} - ${destination}\nTanggal: ${date}\nStatus: ${statusText}?`;
            } else if (status === 'batal') {
                confirmTitle = 'Konfirmasi Pembatalan';
                confirmMessage = `Apakah Anda yakin ingin membatalkan pemesanan untuk:\n\nPelanggan: ${userName}\nTujuan: ${origin} - ${destination}\nTanggal: ${date}\nStatus: ${statusText}?`;
            } else {
                confirmTitle = 'Konfirmasi Perubahan Status';
                confirmMessage = `Apakah Anda yakin ingin mengubah status pemesanan untuk:\n\nPelanggan: ${userName}\nTujuan: ${origin} - ${destination}\nTanggal: ${date}\nStatus: ${statusText}?`;
            }

            Swal.fire({
                title: confirmTitle,
                text: confirmMessage,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Lanjutkan',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Memproses...',
                        text: 'Mohon tunggu sebentar',
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        willOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    this.submit();
                }
            });
        });
    }
});

// ======================
// ADMIN: Delete confirmation for various entities
// ======================
document.addEventListener('DOMContentLoaded', function () {
    // Delete confirmation for rute
    const deleteFormsRute = document.querySelectorAll('.delete-form');
    deleteFormsRute.forEach(form => {
        if (form.querySelector('button').textContent.includes('Hapus')) {
            form.addEventListener('submit', function (e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Yakin hapus rute ini?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        }
    });

    // Delete confirmation for mobil
    const deleteFormsMobil = document.querySelectorAll('.delete-form');
    deleteFormsMobil.forEach(form => {
        if (form.querySelector('button').textContent.includes('Hapus')) {
            form.addEventListener('submit', function (e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Yakin hapus data mobil ini?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        }
    });

    // Delete confirmation for pelanggan
    const deleteFormsPelanggan = document.querySelectorAll('.delete-form');
    deleteFormsPelanggan.forEach(form => {
        if (form.querySelector('button').textContent.includes('Hapus')) {
            form.addEventListener('submit', function (e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Yakin hapus pelanggan ini?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        }
    });

    // Delete confirmation for jadwals
    const deleteFormsJadwals = document.querySelectorAll('.delete-form');
    deleteFormsJadwals.forEach(form => {
        if (form.querySelector('button').textContent.includes('Hapus')) {
            form.addEventListener('submit', function (e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Yakin hapus jadwal ini?',
                    text: 'Data booking terkait juga akan terpengaruh!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        }
    });

    // Delete confirmation for supir
    const deleteFormsSupir = document.querySelectorAll('.delete-form');
    deleteFormsSupir.forEach(form => {
        if (form.querySelector('button')?.textContent.includes('Hapus')) {
            form.addEventListener('submit', function (e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Yakin hapus data supir ini?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        }
    });

    // Dashboard chart initialization - Line Chart
    const dashboardChartCanvasLine = document.getElementById('chartPendapatanLineDashboard');
    if (dashboardChartCanvasLine && dashboardChartCanvasLine.dataset.labels && dashboardChartCanvasLine.dataset.dashboard) {
        const labels = JSON.parse(dashboardChartCanvasLine.dataset.labels);
        const data = JSON.parse(dashboardChartCanvasLine.dataset.data);

        new Chart(dashboardChartCanvasLine, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Pendapatan',
                    data: data,
                    borderColor: '#10b981',
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function (value) {
                                return 'Rp ' + value.toLocaleString('id-ID');
                            }
                        }
                    }
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function (context) {
                                return 'Rp ' + context.parsed.y.toLocaleString('id-ID');
                            }
                        }
                    }
                }
            }
        });
    }

    // Revenue visibility toggle (dashboard)
    const toggleRevenueBtn = document.getElementById('toggle-revenue-visibility');
    const revenueAmount = document.getElementById('revenue-amount');
    const eyeIcon = document.getElementById('eye-icon');

    if (toggleRevenueBtn && revenueAmount && eyeIcon) {
        let isVisible = true;
        toggleRevenueBtn.addEventListener('click', function () {
            isVisible = !isVisible;
            if (isVisible) {
                revenueAmount.style.display = 'block';
                eyeIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />';
            } else {
                revenueAmount.style.display = 'none';
                eyeIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21" />';
            }
        });
    }

    // Revenue visibility toggle (laporan - total)
    const toggleTotalRevenueBtn = document.getElementById('toggle-total-revenue-visibility');
    const totalRevenueAmount = document.getElementById('total-revenue-amount');
    const eyeIconTotal = document.getElementById('eye-icon-total');

    if (toggleTotalRevenueBtn && totalRevenueAmount && eyeIconTotal) {
        let isTotalVisible = true;
        toggleTotalRevenueBtn.addEventListener('click', function () {
            isTotalVisible = !isTotalVisible;
            if (isTotalVisible) {
                totalRevenueAmount.style.display = 'block';
                eyeIconTotal.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />';
            } else {
                totalRevenueAmount.style.display = 'none';
                eyeIconTotal.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21" />';
            }
        });
    }

    // Revenue visibility toggle (laporan - monthly)
    const toggleMonthlyRevenueBtn = document.getElementById('toggle-monthly-revenue-visibility');
    const monthlyRevenueAmount = document.getElementById('monthly-revenue-amount');
    const eyeIconMonthly = document.getElementById('eye-icon-monthly');

    if (toggleMonthlyRevenueBtn && monthlyRevenueAmount && eyeIconMonthly) {
        let isMonthlyVisible = true;
        toggleMonthlyRevenueBtn.addEventListener('click', function () {
            isMonthlyVisible = !isMonthlyVisible;
            if (isMonthlyVisible) {
                monthlyRevenueAmount.style.display = 'block';
                eyeIconMonthly.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />';
            } else {
                monthlyRevenueAmount.style.display = 'none';
                eyeIconMonthly.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21" />';
            }
        });
    }

    // Laporan chart initialization - Bar Chart
    const laporanChartCanvasBar = document.getElementById('chartPendapatanBar');
    if (laporanChartCanvasBar && laporanChartCanvasBar.dataset.labels) {
        const labels = JSON.parse(laporanChartCanvasBar.dataset.labels);
        const data = JSON.parse(laporanChartCanvasBar.dataset.data);

        new Chart(laporanChartCanvasBar, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Pendapatan',
                    data: data,
                    backgroundColor: '#3b82f6',
                    borderColor: '#2563eb',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function (value) {
                                return 'Rp ' + value.toLocaleString('id-ID');
                            }
                        }
                    }
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function (context) {
                                return 'Rp ' + context.parsed.y.toLocaleString('id-ID');
                            }
                        }
                    }
                }
            }
        });
    }

    // Laporan chart initialization - Line Chart
    const laporanChartCanvasLine = document.getElementById('chartPendapatanLine');
    if (laporanChartCanvasLine && laporanChartCanvasLine.dataset.labels) {
        const labels = JSON.parse(laporanChartCanvasLine.dataset.labels);
        const data = JSON.parse(laporanChartCanvasLine.dataset.data);

        new Chart(laporanChartCanvasLine, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Pendapatan',
                    data: data,
                    borderColor: '#10b981',
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function (value) {
                                return 'Rp ' + value.toLocaleString('id-ID');
                            }
                        }
                    }
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function (context) {
                                return 'Rp ' + context.parsed.y.toLocaleString('id-ID');
                            }
                        }
                    }
                }
            }
        });
    }

    // Laporan chart initialization - Pie Chart
    const laporanChartCanvasPie = document.getElementById('chartPendapatanPie');
    if (laporanChartCanvasPie && laporanChartCanvasPie.dataset.labels) {
        const labels = JSON.parse(laporanChartCanvasPie.dataset.labels);
        const data = JSON.parse(laporanChartCanvasPie.dataset.data);

        new Chart(laporanChartCanvasPie, {
            type: 'pie',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Pendapatan',
                    data: data,
                    backgroundColor: [
                        '#3b82f6', // blue
                        '#10b981', // green
                        '#f59e0b', // yellow
                        '#ef4444', // red
                        '#8b5cf6', // purple
                        '#06b6d4', // cyan
                        '#f97316', // orange
                        '#84cc16', // lime
                        '#ec4899', // pink
                        '#6b7280'  // gray
                    ],
                    borderWidth: 2,
                    borderColor: '#ffffff'
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false // Hide legend to reduce clutter
                    },
                    tooltip: {
                        callbacks: {
                            label: function (context) {
                                const label = context.label || '';
                                const value = context.parsed;
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = ((value / total) * 100).toFixed(1);
                                return 'Tanggal ' + label + ': Rp ' + value.toLocaleString('id-ID') + ' (' + percentage + '%)';
                            }
                        }
                    }
                }
            }
        });
    }
});

