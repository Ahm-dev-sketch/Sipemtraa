/* ANIMASI SCROLL - Efek muncul saat scroll */
document.addEventListener('DOMContentLoaded', () => {
    const observer = new IntersectionObserver(entries => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('show');
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.1 });

    setTimeout(() => {
        document.querySelectorAll('.animate-on-scroll').forEach(el => {
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

/* SWEET ALERT - Notifikasi sukses dan error */
document.addEventListener('DOMContentLoaded', function () {
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

/* NAVIGATION & UI COMPONENTS - Menu, sidebar, navbar, dropdown */
document.addEventListener("DOMContentLoaded", () => {
    const toggle = document.getElementById("menu-toggle");
    const menu = document.getElementById("menu");
    const sidebar = document.getElementById("sidebar");
    const sidebarOverlay = document.getElementById("sidebar-overlay");

    if (toggle && menu && !sidebar) {
        toggle.addEventListener("click", () => menu.classList.toggle("hidden"));
    }

    if (toggle && sidebar && sidebarOverlay) {
        toggle.addEventListener("click", () => {
            // toggle sidebar visibility
            sidebar.classList.toggle("-translate-x-full");
            const isHidden = sidebar.classList.contains("-translate-x-full");
            // if sidebar is hidden after toggle -> ensure overlay is hidden and page scroll enabled
            if (isHidden) {
                sidebarOverlay.classList.add("hidden");
                sidebarOverlay.classList.add("pointer-events-none");
                sidebarOverlay.setAttribute('aria-hidden', 'true');
                toggle.setAttribute('aria-expanded', 'false');
                document.body.style.overflow = '';
            } else {
                // sidebar opened -> show overlay and prevent background scroll
                sidebarOverlay.classList.remove("hidden");
                sidebarOverlay.classList.remove("pointer-events-none");
                sidebarOverlay.setAttribute('aria-hidden', 'false');
                toggle.setAttribute('aria-expanded', 'true');
                document.body.style.overflow = 'hidden';
            }
        });

        sidebarOverlay.addEventListener("click", () => {
            sidebar.classList.add("-translate-x-full");
            sidebarOverlay.classList.add("hidden");
            sidebarOverlay.classList.add("pointer-events-none");
            sidebarOverlay.setAttribute('aria-hidden', 'true');
            toggle.setAttribute('aria-expanded', 'false');
            document.body.style.overflow = '';
        });

        const sidebarLinks = sidebar.querySelectorAll("a");
        sidebarLinks.forEach(link => {
            link.addEventListener("click", () => {
                if (window.innerWidth < 768) {
                    sidebar.classList.add("-translate-x-full");
                    sidebarOverlay.classList.add("hidden");
                    sidebarOverlay.classList.add("pointer-events-none");
                    sidebarOverlay.setAttribute('aria-hidden', 'true');
                    toggle.setAttribute('aria-expanded', 'false');
                    document.body.style.overflow = '';
                }
            });
        });
    }

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

    const userMenuBtn = document.getElementById("user-menu-btn");
    const userDropdown = document.getElementById("user-dropdown");
    if (userMenuBtn && userDropdown) {
        const showDropdown = () => {
            userDropdown.classList.remove("hidden", "scale-95");
            setTimeout(() => {
                userDropdown.classList.add("opacity-100", "scale-100");
            }, 10);
        }
        const hideDropdown = () => {
            userDropdown.classList.remove("opacity-100", "scale-100");
            userDropdown.classList.add("scale-95");
            setTimeout(() => userDropdown.classList.add("hidden"), 300);
        }
        userMenuBtn.addEventListener("click", (e) => {
            e.stopPropagation();
            if (userDropdown.classList.contains("hidden")) showDropdown();
            else hideDropdown();
        });
        document.addEventListener("click", (e) => {
            if (!userMenuBtn.contains(e.target) && !userDropdown.contains(e.target)) {
                hideDropdown();
            }
        });
    }

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

    /* SEAT BOOKING SYSTEM - Sistem pemesanan kursi */
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
                        // Reset any previous selections and update availability for the new jadwal
                        seatCheckboxes.forEach(cb => {
                            const seatDiv = cb.nextElementSibling;
                            // Clear previous checked state for a fresh selection when date changes
                            cb.checked = false;

                            if (bookedSeats.includes(cb.value)) {
                                cb.disabled = true;
                                seatDiv.className =
                                    "w-16 h-16 flex items-center justify-center rounded bg-red-500 text-white cursor-not-allowed";
                            } else {
                                cb.disabled = false;
                                seatDiv.className =
                                    "w-16 h-16 flex items-center justify-center rounded bg-green-500 text-white hover:bg-blue-500";
                            }
                        });

                        // Update the summary / total / button state after clearing selections
                        if (typeof updateDisplay === 'function') {
                            updateDisplay();
                        }
                    });
            } else {
                seatCheckboxes.forEach(cb => {
                    cb.disabled = true;
                    cb.checked = false;
                    cb.nextElementSibling.className =
                        "w-16 h-16 flex items-center justify-center rounded bg-gray-300 text-black";
                });
                if (typeof updateDisplay === 'function') updateDisplay();
            }
        });
    }

    const selectedSeatsDisplay = document.getElementById('selected-seats');
    const totalPriceDisplay = document.getElementById('total-price');
    const bookButton = document.getElementById('book-button');
    const seatForm = document.getElementById('seat-form');
    const pricePerSeat = seatForm ? parseInt(seatForm.dataset.pricePerSeat) : 0;

    function updateDisplay() {
        const selectedSeats = Array.from(seatCheckboxes)
            .filter(cb => cb.checked && !cb.disabled)
            .map(cb => cb.value);

        if (selectedSeatsDisplay) {
            selectedSeatsDisplay.textContent =
                selectedSeats.length > 0
                    ? selectedSeats.join(', ')
                    : 'Belum ada kursi dipilih';
        }

        if (totalPriceDisplay) {
            const totalPrice = selectedSeats.length * pricePerSeat;
            totalPriceDisplay.textContent =
                'Rp ' + totalPrice.toLocaleString('id-ID');
        }

        if (bookButton) {
            bookButton.disabled = selectedSeats.length === 0;
        }

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

    seatCheckboxes.forEach(cb => {
        cb.addEventListener('change', function () {
            updateDisplay();
            const seatDiv = this.nextElementSibling;
            if (this.checked) {
                seatDiv.classList.remove("bg-gradient-to-br", "from-blue-400", "to-blue-500", "border-blue-600", "hover:from-blue-500", "hover:to-blue-600", "hover:shadow-blue-500/50");
                seatDiv.classList.add("bg-gradient-to-br", "from-green-400", "to-green-500", "border-green-600", "shadow-xl", "shadow-green-500/50");
            } else {
                seatDiv.classList.remove("bg-gradient-to-br", "from-green-400", "to-green-500", "border-green-600", "shadow-xl", "shadow-green-500/50");
                seatDiv.classList.add("bg-gradient-to-br", "from-blue-400", "to-blue-500", "border-blue-600", "hover:from-blue-500", "hover:to-blue-600", "hover:shadow-blue-500/50");
            }
        });
    });

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

    updateDisplay();
});


/* UTILITY FUNCTIONS - Fungsi utilitas */
function getCurrentPage() {
    return window.location.pathname;
}

/* PAGE SPECIFIC FUNCTIONS - Fungsi khusus halaman tertentu */

/* RIWAYAT PAGE - Halaman riwayat pemesanan */
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

/* BOOKING STEP1 PAGE - Halaman langkah pertama pemesanan */
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


/* ADMIN RUTE CREATE PAGE - Halaman admin membuat rute baru */
if (getCurrentPage().includes('/admin/rute/create')) {
    function formatNumber(num) {
        return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }

    function unformatNumber(str) {
        return str.replace(/\./g, '');
    }

    const hargaInput = document.getElementById('harga_tiket');
    if (hargaInput) {
        hargaInput.addEventListener('input', function (e) {
            let value = e.target.value;

            value = value.replace(/[^\d.]/g, '');

            value = unformatNumber(value);

            if (value) {
                value = formatNumber(value);
            }

            e.target.value = value;
        });
    }

    const formRute = document.getElementById('formRute');
    if (formRute) {
        formRute.addEventListener('submit', function (e) {
            e.preventDefault();

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


/* ADMIN SUPIR CREATE PAGE - Halaman admin membuat supir baru */
if (getCurrentPage().includes('/admin/supir/create')) {
    const formSupir = document.getElementById('formSupir');
    if (formSupir) {
        formSupir.addEventListener('submit', function (e) {
            e.preventDefault();
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

/* 3D TILT EFFECT - Efek 3D tilt untuk gambar home */
document.addEventListener('DOMContentLoaded', function () {
    const card = document.querySelector('[data-tilt]');
    if (!card) return;

    let raf = null;

    function updateTransform(x, y, rect) {
        const cx = rect.width / 2;
        const cy = rect.height / 2;
        const rx = ((y - cy) / rect.height) * 10; // rotateX
        const ry = ((x - cx) / rect.width) * -10; // rotateY
        card.style.transform = `rotateX(${rx}deg) rotateY(${ry}deg)`;
    }

    card.addEventListener('mousemove', function (e) {
        const rect = card.getBoundingClientRect();
        const x = e.clientX - rect.left;
        const y = e.clientY - rect.top;
        if (raf) cancelAnimationFrame(raf);
        raf = requestAnimationFrame(function () { updateTransform(x, y, rect); });
    });

    card.addEventListener('mouseleave', function () {
        if (raf) cancelAnimationFrame(raf);
        raf = requestAnimationFrame(function () {
            card.style.transform = '';
        });
    });
});


/* ADMIN MOBIL CREATE PAGE - Halaman admin membuat mobil baru */
if (getCurrentPage().includes('/admin/mobil/create')) {
    const formMobil = document.getElementById('formMobil');
    if (formMobil) {
        formMobil.addEventListener('submit', function (e) {
            e.preventDefault();
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

/* ADMIN PELANGGAN CREATE PAGE - Halaman admin membuat pelanggan baru */
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

/* ADMIN JADWALS CREATE PAGE - Halaman admin membuat jadwal baru */
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

/* HOME PAGE - Halaman utama dengan counter animasi */
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

/* ADMIN BOOKINGS STATUS CHANGE - Perubahan status booking admin */
document.querySelectorAll('form[action*="bookings"] input[name="status"]').forEach(input => {
    const form = input.closest('form');
    if (form) {
        form.addEventListener('submit', function (e) {
            e.preventDefault();

            const status = e.submitter.value;
            const statusText = status === 'setuju' ? 'Setuju' : 'Batal';

            // Find a sensible container: table row (desktop) or the mobile card wrapper
            const container = this.closest('tr') || this.closest('.bg-white') || this.closest('div');

            // helper to safely read text from selectors with fallbacks
            const getText = (selPrimary, selFallback) => {
                try {
                    const el = container ? (container.querySelector(selPrimary) || (selFallback ? container.querySelector(selFallback) : null)) : null;
                    return el ? el.textContent.trim() : '-';
                } catch (e) {
                    return '-';
                }
            };

            const userName = getText('td:nth-child(1) .ml-4 .text-sm', 'td:nth-child(1)');
            const origin = getText('td:nth-child(2) .ml-3 .font-medium', 'td:nth-child(2)');
            const destination = getText('td:nth-child(2) .ml-3 .text-xs', 'td:nth-child(2)');
            const date = getText('td:nth-child(3) .text-sm', 'td:nth-child(3)');
            const time = getText('td:nth-child(3) .text-xs', null);
            const mobilName = getText('td:nth-child(4) .text-sm', 'td:nth-child(4)');
            const mobilNumber = getText('td:nth-child(4) .text-xs', null);

            let confirmTitle = '';
            if (status === 'setuju') {
                confirmTitle = 'Konfirmasi Persetujuan';
            } else if (status === 'batal') {
                confirmTitle = 'Konfirmasi Pembatalan';
            } else {
                confirmTitle = 'Konfirmasi Perubahan Status';
            }

            // Build an HTML message for Swal so content is readable and labeled
            const htmlMessage = `
                <div style="text-align:left; line-height:1.4">
                    <p><strong>Pelanggan:</strong> ${userName}</p>
                    <p><strong>Rute:</strong> ${origin} → ${destination}</p>
                    <p><strong>Tanggal:</strong> ${date}</p>
                    <p><strong>Jam:</strong> ${time}</p>
                    <p><strong>Mobil:</strong> ${mobilName}${mobilNumber && mobilNumber !== '-' ? ' (' + mobilNumber + ')' : ''}</p>
                    <p><strong>Status:</strong> ${statusText}</p>
                </div>
            `;

            Swal.fire({
                title: confirmTitle,
                html: htmlMessage,
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

/* DELETE CONFIRMATIONS - Konfirmasi penghapusan data */
document.addEventListener('DOMContentLoaded', function () {
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

    /* DASHBOARD CHART LINE - Chart garis dashboard */
    const dashboardChartCanvasLine = document.getElementById('chartPendapatanLineDashboard');
    if (dashboardChartCanvasLine && dashboardChartCanvasLine.dataset.labels && dashboardChartCanvasLine.dataset.dashboard) {
        const labels = JSON.parse(dashboardChartCanvasLine.dataset.labels);
        const data = JSON.parse(dashboardChartCanvasLine.dataset.data);

        const ctx = dashboardChartCanvasLine.getContext('2d');
        const gradient = ctx.createLinearGradient(0, 0, 0, 300);
        gradient.addColorStop(0, 'rgba(16, 185, 129, 0.3)');
        gradient.addColorStop(0.5, 'rgba(16, 185, 129, 0.15)');
        gradient.addColorStop(1, 'rgba(16, 185, 129, 0.02)');

        new Chart(dashboardChartCanvasLine, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Pendapatan (Rp)',
                    data: data,
                    borderColor: '#10b981',
                    backgroundColor: gradient,
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 5,
                    pointHoverRadius: 7,
                    pointBackgroundColor: '#10b981',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2,
                    pointHoverBackgroundColor: '#059669',
                    pointHoverBorderColor: '#ffffff',
                    pointHoverBorderWidth: 3
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                interaction: {
                    intersect: false,
                    mode: 'index'
                },
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                        align: 'end',
                        labels: {
                            usePointStyle: true,
                            pointStyle: 'circle',
                            padding: 15,
                            font: {
                                size: 12,
                                weight: '600'
                            },
                            color: '#1e293b'
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(15, 23, 42, 0.95)',
                        titleColor: '#ffffff',
                        bodyColor: '#e2e8f0',
                        titleFont: {
                            size: 13,
                            weight: 'bold'
                        },
                        bodyFont: {
                            size: 12
                        },
                        padding: 12,
                        cornerRadius: 8,
                        displayColors: true,
                        boxPadding: 6,
                        callbacks: {
                            label: function (context) {
                                return 'Rp ' + context.parsed.y.toLocaleString('id-ID');
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            font: {
                                size: 11,
                                weight: '500'
                            },
                            color: '#64748b'
                        }
                    },
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(148, 163, 184, 0.1)',
                            drawBorder: false
                        },
                        ticks: {
                            font: {
                                size: 11,
                                weight: '500'
                            },
                            color: '#64748b',
                            callback: function (value) {
                                return 'Rp ' + (value / 1000000).toFixed(0) + 'jt';
                            },
                            padding: 8
                        }
                    }
                }
            }
        });
    }

    /* REVENUE VISIBILITY TOGGLES - Toggle visibilitas pendapatan */
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

    /* LAPORAN CHART BAR - Chart batang laporan */
    const laporanChartCanvasBar = document.getElementById('chartPendapatanBar');
    if (laporanChartCanvasBar && laporanChartCanvasBar.dataset.labels) {
        const labels = JSON.parse(laporanChartCanvasBar.dataset.labels);
        const data = JSON.parse(laporanChartCanvasBar.dataset.data);

        const ctx = laporanChartCanvasBar.getContext('2d');
        const barGradient = ctx.createLinearGradient(0, 0, 0, 400);
        barGradient.addColorStop(0, '#6366f1');
        barGradient.addColorStop(1, '#8b5cf6');

        new Chart(laporanChartCanvasBar, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Pendapatan (Rp)',
                    data: data,
                    backgroundColor: barGradient,
                    borderColor: 'transparent',
                    borderWidth: 0,
                    borderRadius: 8,
                    borderSkipped: false,
                    hoverBackgroundColor: '#7c3aed'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                interaction: {
                    intersect: false,
                    mode: 'index'
                },
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                        align: 'end',
                        labels: {
                            usePointStyle: true,
                            pointStyle: 'rectRounded',
                            padding: 15,
                            font: {
                                size: 12,
                                weight: '600'
                            },
                            color: '#1e293b'
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(15, 23, 42, 0.95)',
                        titleColor: '#ffffff',
                        bodyColor: '#e2e8f0',
                        titleFont: {
                            size: 13,
                            weight: 'bold'
                        },
                        bodyFont: {
                            size: 12
                        },
                        padding: 12,
                        cornerRadius: 8,
                        displayColors: true,
                        boxPadding: 6,
                        callbacks: {
                            label: function (context) {
                                return 'Rp ' + context.parsed.y.toLocaleString('id-ID');
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            font: {
                                size: 11,
                                weight: '500'
                            },
                            color: '#64748b'
                        }
                    },
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(148, 163, 184, 0.1)',
                            drawBorder: false
                        },
                        ticks: {
                            font: {
                                size: 11,
                                weight: '500'
                            },
                            color: '#64748b',
                            callback: function (value) {
                                return 'Rp ' + (value / 1000000).toFixed(0) + 'jt';
                            },
                            padding: 8
                        }
                    }
                }
            }
        });
    }

    const laporanChartCanvasLine = document.getElementById('chartPendapatanLine');
    if (laporanChartCanvasLine && laporanChartCanvasLine.dataset.labels) {
        const labels = JSON.parse(laporanChartCanvasLine.dataset.labels);
        const data = JSON.parse(laporanChartCanvasLine.dataset.data);

        const ctx = laporanChartCanvasLine.getContext('2d');
        const lineGradient = ctx.createLinearGradient(0, 0, 0, 300);
        lineGradient.addColorStop(0, 'rgba(16, 185, 129, 0.3)');
        lineGradient.addColorStop(0.5, 'rgba(16, 185, 129, 0.15)');
        lineGradient.addColorStop(1, 'rgba(16, 185, 129, 0.02)');

        new Chart(laporanChartCanvasLine, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Pendapatan (Rp)',
                    data: data,
                    borderColor: '#10b981',
                    backgroundColor: lineGradient,
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 5,
                    pointHoverRadius: 7,
                    pointBackgroundColor: '#10b981',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2,
                    pointHoverBackgroundColor: '#059669',
                    pointHoverBorderColor: '#ffffff',
                    pointHoverBorderWidth: 3
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                interaction: {
                    intersect: false,
                    mode: 'index'
                },
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                        align: 'end',
                        labels: {
                            usePointStyle: true,
                            pointStyle: 'circle',
                            padding: 15,
                            font: {
                                size: 12,
                                weight: '600'
                            },
                            color: '#1e293b'
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(15, 23, 42, 0.95)',
                        titleColor: '#ffffff',
                        bodyColor: '#e2e8f0',
                        titleFont: {
                            size: 13,
                            weight: 'bold'
                        },
                        bodyFont: {
                            size: 12
                        },
                        padding: 12,
                        cornerRadius: 8,
                        displayColors: true,
                        boxPadding: 6,
                        callbacks: {
                            label: function (context) {
                                return 'Rp ' + context.parsed.y.toLocaleString('id-ID');
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            font: {
                                size: 11,
                                weight: '500'
                            },
                            color: '#64748b'
                        }
                    },
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(148, 163, 184, 0.1)',
                            drawBorder: false
                        },
                        ticks: {
                            font: {
                                size: 11,
                                weight: '500'
                            },
                            color: '#64748b',
                            callback: function (value) {
                                return 'Rp ' + (value / 1000000).toFixed(0) + 'jt';
                            },
                            padding: 8
                        }
                    }
                }
            }
        });
    }

    /* LAPORAN CHART PIE - Chart pie laporan */
    const laporanChartCanvasPie = document.getElementById('chartPendapatanPie');
    if (laporanChartCanvasPie && laporanChartCanvasPie.dataset.labels) {
        const labels = JSON.parse(laporanChartCanvasPie.dataset.labels);
        const data = JSON.parse(laporanChartCanvasPie.dataset.data);

        new Chart(laporanChartCanvasPie, {
            type: 'doughnut',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Pendapatan',
                    data: data,
                    backgroundColor: [
                        '#6366f1',
                        '#8b5cf6',
                        '#ec4899',
                        '#f43f5e',
                        '#f97316',
                        '#f59e0b',
                        '#eab308',
                        '#84cc16',
                        '#22c55e',
                        '#10b981',
                        '#14b8a6',
                        '#06b6d4',
                        '#0ea5e9',
                        '#3b82f6',
                        '#6366f1',
                    ],
                    borderWidth: 3,
                    borderColor: '#ffffff',
                    hoverBorderWidth: 4,
                    hoverBorderColor: '#ffffff',
                    hoverOffset: 15
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                cutout: '65%',
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: 'rgba(15, 23, 42, 0.95)',
                        titleColor: '#ffffff',
                        bodyColor: '#e2e8f0',
                        titleFont: {
                            size: 14,
                            weight: 'bold'
                        },
                        bodyFont: {
                            size: 13
                        },
                        padding: 16,
                        cornerRadius: 10,
                        displayColors: true,
                        boxPadding: 8,
                        callbacks: {
                            title: function (context) {
                                return 'Tanggal ' + context[0].label;
                            },
                            label: function (context) {
                                const value = context.parsed;
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = ((value / total) * 100).toFixed(1);
                                return [
                                    'Pendapatan: Rp ' + value.toLocaleString('id-ID'),
                                    'Persentase: ' + percentage + '%'
                                ];
                            }
                        }
                    }
                },
                animation: {
                    animateRotate: true,
                    animateScale: true
                }
            },
            plugins: [{
                id: 'centerText',
                beforeDraw: function (chart) {
                    const ctx = chart.ctx;
                    const width = chart.width;
                    const height = chart.height;
                    const total = chart.config.data.datasets[0].data.reduce((a, b) => a + b, 0);

                    ctx.restore();
                    ctx.font = 'bold 16px sans-serif';
                    ctx.textBaseline = 'middle';
                    ctx.fillStyle = '#64748b';

                    const text = 'Total Pendapatan';
                    const textX = Math.round((width - ctx.measureText(text).width) / 2);
                    const textY = height / 2 - 15;

                    ctx.fillText(text, textX, textY);

                    ctx.font = 'bold 18px sans-serif';
                    ctx.fillStyle = '#1e293b';
                    const totalText = 'Rp ' + (total / 1000000).toFixed(1) + 'jt';
                    const totalX = Math.round((width - ctx.measureText(totalText).width) / 2);
                    const totalY = height / 2 + 10;

                    ctx.fillText(totalText, totalX, totalY);
                    ctx.save();
                }
            }]
        });
    }
});

document.addEventListener('DOMContentLoaded', function () {
    function formatNumber(num) {
        return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }

    function autoFormatPrice(inputId) {
        const input = document.getElementById(inputId);
        if (input) {
            input.addEventListener('input', function (e) {
                let value = this.value.replace(/\./g, '');
                if (value && !isNaN(value)) {
                    this.value = formatNumber(value);
                }
            });
        }
    }

    function cleanPriceInput(formId, inputId) {
        const form = document.getElementById(formId);
        if (form) {
            form.addEventListener('submit', function (e) {
                const hargaInput = document.getElementById(inputId);
                if (hargaInput) {
                    hargaInput.value = hargaInput.value.replace(/\./g, '');
                }
            });
        }
    }

    autoFormatPrice('harga_tiket');
    autoFormatPrice('harga');

    cleanPriceInput('formRute', 'harga_tiket');
    cleanPriceInput('formRuteEdit', 'harga_tiket');
    cleanPriceInput('formJadwal', 'harga');
    cleanPriceInput('formJadwalEdit', 'harga');

    if (typeof flatpickr !== 'undefined') {
        const dateInputs = document.querySelectorAll('input[type="date"]');
        dateInputs.forEach(input => {
            const minDate = input.getAttribute('min') || 'today';

            flatpickr(input, {
                dateFormat: 'Y-m-d',
                altInput: true,
                altFormat: 'j F Y',
                minDate: minDate,
                locale: {
                    firstDayOfWeek: 1,
                    weekdays: {
                        shorthand: ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'],
                        longhand: ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu']
                    },
                    months: {
                        shorthand: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
                        longhand: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember']
                    },
                    rangeSeparator: ' sampai ',
                    weekAbbreviation: 'Mgg',
                    scrollTitle: 'Scroll untuk menambah',
                    toggleTitle: 'Klik untuk toggle'
                },
                disableMobile: false,
                allowInput: false,
                clickOpens: true
            });
        });
    }

    const ruteSelect = document.getElementById('rute_id');
    const jamSelect = document.getElementById('jam');
    const hargaInput = document.getElementById('harga');

    if (ruteSelect && jamSelect) {
        ruteSelect.addEventListener('change', function () {
            const ruteId = this.value;

            if (!ruteId) {
                jamSelect.innerHTML = '<option value="" disabled selected>-- Pilih rute terlebih dahulu --</option>';
                if (hargaInput) hargaInput.value = '';
                return;
            }

            fetch(`/admin/api/rute/${ruteId}/jam`)
                .then(response => response.json())
                .then(data => {
                    jamSelect.innerHTML = '<option value="" disabled selected>-- Pilih jam keberangkatan --</option>';

                    if (data.jam_keberangkatan && data.jam_keberangkatan.length > 0) {
                        data.jam_keberangkatan.forEach(jam => {
                            const option = document.createElement('option');
                            option.value = jam;
                            option.textContent = jam;
                            jamSelect.appendChild(option);
                        });
                    } else {
                        jamSelect.innerHTML = '<option value="" disabled>Tidak ada jam keberangkatan</option>';
                    }
                })
                .catch(error => {
                    console.error('Error fetching jam keberangkatan:', error);
                    jamSelect.innerHTML = '<option value="" disabled>Error loading data</option>';
                });

            if (hargaInput) {
                fetch(`/admin/api/rute/${ruteId}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.harga_tiket) {
                            const formattedHarga = parseInt(data.harga_tiket).toLocaleString('id-ID');
                            hargaInput.value = formattedHarga;
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching harga:', error);
                    });
            }
        });
    }
});

document.addEventListener('DOMContentLoaded', function () {
    // Make image "loaded" handling resilient to browser deferred load events
    // (some browsers may defer or throttle load events under tracking protection).
    document.querySelectorAll('img').forEach(img => {
        // If already complete, mark immediately
        if (img.complete) {
            img.classList.add('loaded');
            return;
        }

        // Prefer using the modern decode() promise when available
        if (typeof img.decode === 'function') {
            img.decode().then(() => {
                img.classList.add('loaded');
            }).catch(() => {
                // decode() may fail or be rejected if the image is deferred; fall back
                const onLoad = function () {
                    img.classList.add('loaded');
                    img.removeEventListener('load', onLoad);
                };
                img.addEventListener('load', onLoad);
            });
        } else {
            // Older browsers: listen for the load event
            const onLoad = function () {
                img.classList.add('loaded');
                img.removeEventListener('load', onLoad);
            };
            img.addEventListener('load', onLoad);
        }

        // Safety timeout: if neither load nor decode fires (e.g. heavy throttling),
        // mark the image as loaded after a short delay so placeholders don't stick forever.
        setTimeout(() => {
            if (!img.classList.contains('loaded')) {
                img.classList.add('loaded');
            }
        }, 3000);
    });
});

window.addEventListener('load', function () {
    document.querySelectorAll('img[fetchpriority="high"]').forEach(img => {
        img.style.opacity = '1';
    });
});

document.addEventListener('DOMContentLoaded', function () {
    const mapContainers = document.querySelectorAll('.map-container');

    mapContainers.forEach(container => {
        const placeholder = container.querySelector('.map-placeholder');
        const mapSrc = container.getAttribute('data-map-src');

        if (placeholder && mapSrc) {
            placeholder.addEventListener('click', function () {
                const iframe = document.createElement('iframe');
                iframe.src = mapSrc;
                iframe.width = '100%';
                iframe.height = '500';
                iframe.style.border = '0';
                iframe.allowFullscreen = true;
                iframe.loading = 'lazy';
                iframe.referrerPolicy = 'no-referrer-when-downgrade';

                container.innerHTML = '';
                container.appendChild(iframe);
            });
        }
    });
});
