/* LibraryReg — Registration, Members List, dan Login (front end + localStorage) */
(function () {
  "use strict";

  /* ---------- Konfigurasi ---------- */
  var STORAGE_KEY = "libraryreg_members";
  var SESSION_KEY = "libraryreg_session";
  var REQUIRE_LOGIN_FOR_MEMBERS = false; // true = Members List hanya bisa dibuka setelah login
  var DEMO_USER = { username: "petugas", password: "perpus123" }; // akun demo, hanya front end

  var JENIS = ["Mahasiswa", "Dosen", "Tenaga Kependidikan"];
  var BADGE = { "Mahasiswa": "mahasiswa", "Dosen": "dosen", "Tenaga Kependidikan": "tendik" };
  var FIELDS = ["nama", "nomorInduk", "jenis", "prodi", "telepon", "email", "alamat"];
  var LABELS = {
    nama: "Nama Lengkap",
    nomorInduk: "Nomor Induk",
    jenis: "Jenis Keanggotaan",
    prodi: "Program Studi",
    telepon: "Nomor Telepon",
    email: "Email",
    alamat: "Alamat",
    dibuat: "Terdaftar pada"
  };

  // Data contoh
  var SEED = [
    { id: "seed1", nama: "Ayudya Devina Azzahra", nomorInduk: "5025251101", jenis: "Mahasiswa",
      prodi: "Teknik Informatika", telepon: "081234567801", email: "ayudyadevina@kampus.ac.id",
      alamat: "Jl. Mawar No. 12, Surabaya", dibuat: "2026-10-01T08:00:00.000Z" },
    { id: "seed2", nama: "Budi Santoso", nomorInduk: "198501012010011001", jenis: "Dosen",
      prodi: "Sistem Informasi", telepon: "081234567802", email: "budi.santoso@kampus.ac.id",
      alamat: "Jl. Melati No. 5, Sidoarjo", dibuat: "2026-10-02T08:00:00.000Z" },
    { id: "seed3", nama: "Rina Wulandari", nomorInduk: "199002022015021002", jenis: "Tenaga Kependidikan",
      prodi: "Bagian Akademik", telepon: "081234567803", email: "rina.wulandari@kampus.ac.id",
      alamat: "Jl. Kenanga No. 8, Surabaya", dibuat: "2026-10-03T08:00:00.000Z" }
  ];

  /* ---------- Penyimpanan ---------- */
  function loadMembers() {
    try {
      var raw = localStorage.getItem(STORAGE_KEY);
      var data = raw ? JSON.parse(raw) : [];
      return Array.isArray(data) ? data : [];
    } catch (e) {
      return [];
    }
  }

  function saveMembers(list) {
    try {
      localStorage.setItem(STORAGE_KEY, JSON.stringify(list));
      return true;
    } catch (e) {
      return false;
    }
  }

  function ensureSeed() {
    try {
      if (localStorage.getItem(STORAGE_KEY) === null) saveMembers(SEED);
    } catch (e) { /* abaikan */ }
  }

  function uid() {
    return Date.now().toString(36) + Math.random().toString(36).slice(2, 7);
  }

  function isLoggedIn() {
    try { return !!sessionStorage.getItem(SESSION_KEY); } catch (e) { return false; }
  }

  function formatDate(iso) {
    var d = new Date(iso);
    return isNaN(d) ? "-" : d.toLocaleDateString("id-ID", { day: "numeric", month: "long", year: "numeric" });
  }

  /* ---------- Validasi ---------- */
  function validate(data, ignoreId) {
    var e = {};
    var list = loadMembers();

    if (!data.nama) e.nama = "Nama lengkap wajib diisi.";
    else if (data.nama.length < 3) e.nama = "Nama minimal 3 karakter.";
    else if (!/^[\p{L}\s.'-]+$/u.test(data.nama)) e.nama = "Nama hanya boleh berisi huruf, spasi, titik, atau tanda hubung.";

    if (!data.nomorInduk) e.nomorInduk = "Nomor induk wajib diisi.";
    else if (!/^\d{5,20}$/.test(data.nomorInduk)) e.nomorInduk = "Nomor induk harus berupa angka (5–20 digit).";
    else if (list.some(function (m) { return m.nomorInduk === data.nomorInduk && m.id !== ignoreId; })) {
      e.nomorInduk = "Nomor induk sudah terdaftar.";
    }

    if (!data.jenis) e.jenis = "Pilih jenis keanggotaan.";
    else if (JENIS.indexOf(data.jenis) === -1) e.jenis = "Jenis keanggotaan tidak valid.";

    if (!data.prodi) e.prodi = "Program studi wajib diisi.";

    if (!data.telepon) e.telepon = "Nomor telepon wajib diisi.";
    else if (!/^(\+62|62|0)8\d{7,12}$/.test(data.telepon.replace(/[\s-]/g, ""))) {
      e.telepon = "Format nomor telepon tidak valid (contoh: 081234567890).";
    }

    if (!data.email) e.email = "Email wajib diisi.";
    else if (!/^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/.test(data.email)) e.email = "Format email tidak valid.";

    if (!data.alamat) e.alamat = "Alamat wajib diisi.";
    else if (data.alamat.length < 10) e.alamat = "Alamat minimal 10 karakter.";

    return e;
  }

  /* ---------- Form ---------- */
  // Satu sumber kolom formulir, dipakai Registration dan modal Edit
  function fieldsHTML(p) {
    function wrap(col, id, label, control) {
      return '<div class="' + col + '"><label class="form-label" for="' + id + '">' + label +
        ' <span class="text-danger" aria-hidden="true">*</span></label>' + control +
        '<div class="invalid-feedback"></div></div>';
    }
    function text(name, label, type, placeholder, col, extra) {
      var id = p + "-" + name;
      return wrap(col, id, label,
        '<input class="form-control" id="' + id + '" name="' + name + '" type="' + type +
        '" placeholder="' + placeholder + '" ' + (extra || "") + " required>");
    }
    var options = '<option value="">Pilih jenis keanggotaan</option>' +
      JENIS.map(function (j) { return '<option value="' + j + '">' + j + "</option>"; }).join("");

    return text("nama", "Nama Lengkap", "text", "Contoh: Siti Aisyah", "col-12", 'autocomplete="name"') +
      text("nomorInduk", "Nomor Induk (NRP)", "text", "Hanya angka", "col-md-6", 'inputmode="numeric"') +
      wrap("col-md-6", p + "-jenis", "Jenis Keanggotaan",
        '<select class="form-select" id="' + p + '-jenis" name="jenis" required>' + options + "</select>") +
      text("prodi", "Program Studi / Unit", "text", "Contoh: Teknik Informatika", "col-12") +
      text("telepon", "Nomor Telepon", "tel", "081234567890", "col-md-6", 'autocomplete="tel"') +
      text("email", "Email", "email", "nama@kampus.ac.id", "col-md-6", 'autocomplete="email"') +
      wrap("col-12", p + "-alamat", "Alamat",
        '<textarea class="form-control" id="' + p + '-alamat" name="alamat" rows="3" placeholder="Alamat lengkap" required></textarea>');
  }

  function readForm(form) {
    var data = {};
    FIELDS.forEach(function (n) { data[n] = (form.elements[n].value || "").trim(); });
    return data;
  }

  function showErrors(form, errors) {
    Array.prototype.forEach.call(form.elements, function (el) {
      if (!el.name) return;
      var msg = errors[el.name] || "";
      el.classList.toggle("is-invalid", !!msg);
      var fb = el.parentElement.querySelector(".invalid-feedback");
      if (fb) fb.textContent = msg;
    });
    var first = form.querySelector(".is-invalid");
    if (first) first.focus();
  }

  function clearOnInput(form) {
    form.addEventListener("input", function (e) {
      if (e.target.classList) e.target.classList.remove("is-invalid");
    });
  }

  /* ---------- Notifikasi (Bootstrap Toast) ---------- */
  function toast(message, type) {
    var box = document.getElementById("toast-container");
    if (!box) {
      box = document.createElement("div");
      box.id = "toast-container";
      box.className = "toast-container position-fixed top-0 end-0 p-3";
      box.style.zIndex = "1100";
      document.body.appendChild(box);
    }
    var el = document.createElement("div");
    el.className = "toast align-items-center border-0 " + (type === "error" ? "toast-error" : "toast-success");
    el.setAttribute("role", type === "error" ? "alert" : "status");
    el.setAttribute("aria-live", type === "error" ? "assertive" : "polite");
    el.setAttribute("aria-atomic", "true");
    el.innerHTML =
      '<div class="d-flex"><div class="toast-body"></div>' +
      '<button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Tutup"></button></div>';
    el.querySelector(".toast-body").textContent = message;
    box.appendChild(el);
    var t = new bootstrap.Toast(el, { delay: 4500 });
    el.addEventListener("hidden.bs.toast", function () { el.remove(); });
    t.show();
  }

  /* ---------- Navbar: tombol Login berubah jadi Logout ---------- */
  function initNavbar() {
    var link = document.getElementById("nav-auth");
    if (!link || !isLoggedIn()) return;
    link.textContent = "Logout";
    link.setAttribute("href", "index.html");
    link.addEventListener("click", function () {
      try { sessionStorage.removeItem(SESSION_KEY); } catch (e) { /* abaikan */ }
    });
  }

  /* ---------- Halaman Registration ---------- */
  function initRegistration() {
    ensureSeed();
    var form = document.getElementById("registration-form");
    form.querySelector(".form-fields").innerHTML = fieldsHTML("reg");
    clearOnInput(form);

    form.addEventListener("reset", function () {
      setTimeout(function () { showErrors(form, {}); }, 0);
    });

    form.addEventListener("submit", function (e) {
      e.preventDefault();
      var data = readForm(form);
      var errors = validate(data, null);
      showErrors(form, errors);
      if (Object.keys(errors).length) {
        toast("Periksa kembali isian formulir.", "error");
        return;
      }

      var list = loadMembers();
      data.id = uid();
      data.dibuat = new Date().toISOString();
      list.push(data);

      if (!saveMembers(list)) {
        toast("Gagal menyimpan data. Penyimpanan browser tidak tersedia.", "error");
        return;
      }
      form.reset();
      toast("Pendaftaran berhasil. " + data.nama + " ditambahkan ke daftar anggota.", "success");
    });
  }

  /* ---------- Halaman Members List ---------- */
  function initMembers() {
    if (REQUIRE_LOGIN_FOR_MEMBERS && !isLoggedIn()) {
      window.location.replace("login.html");
      return;
    }
    ensureSeed();

    var tbody = document.getElementById("member-table-body");
    var searchInput = document.getElementById("search-input");
    var countEl = document.getElementById("member-count");
    var detailEl = document.getElementById("detailModal");
    var editEl = document.getElementById("editModal");
    var deleteEl = document.getElementById("deleteModal");
    var editForm = document.getElementById("edit-form");

    var members = loadMembers();
    var currentId = null;

    editForm.querySelector(".form-fields").innerHTML = fieldsHTML("edit");
    clearOnInput(editForm);

    function modal(el) { return bootstrap.Modal.getOrCreateInstance(el); }

    function findMember(id) {
      for (var i = 0; i < members.length; i++) if (members[i].id === id) return members[i];
      return null;
    }

    function cell(text) {
      var td = document.createElement("td");
      td.textContent = text;
      return td;
    }

    function actionButton(label, action, member, cls) {
      var b = document.createElement("button");
      b.type = "button";
      b.className = "btn btn-compact me-1 " + cls;
      b.textContent = label;
      b.setAttribute("data-action", action);
      b.setAttribute("data-id", member.id);
      b.setAttribute("aria-label", label + " " + member.nama);
      return b;
    }

    function render() {
      var q = searchInput.value.trim().toLowerCase();
      var rows = members.filter(function (m) {
        return !q || m.nama.toLowerCase().indexOf(q) > -1 || String(m.nomorInduk).toLowerCase().indexOf(q) > -1;
      });

      tbody.innerHTML = "";

      if (!rows.length) {
        var tr = document.createElement("tr");
        var td = document.createElement("td");
        td.colSpan = 6;
        td.className = "text-center text-muted py-5";
        td.textContent = members.length
          ? "Tidak ada anggota yang cocok dengan pencarian."
          : "Belum ada anggota. Daftarkan anggota lewat halaman Registration.";
        tr.appendChild(td);
        tbody.appendChild(tr);
      }

      rows.forEach(function (m, i) {
        var tr = document.createElement("tr");
        tr.appendChild(cell(i + 1));
        tr.appendChild(cell(m.nama));
        tr.appendChild(cell(m.nomorInduk));
        tr.appendChild(cell(m.prodi));

        var tdJenis = document.createElement("td");
        var badge = document.createElement("span");
        badge.className = "badge badge-jenis badge-jenis--" + (BADGE[m.jenis] || "mahasiswa");
        badge.textContent = m.jenis;
        tdJenis.appendChild(badge);
        tr.appendChild(tdJenis);

        var tdAct = document.createElement("td");
        tdAct.className = "text-nowrap";
        tdAct.appendChild(actionButton("Detail", "detail", m, "btn-brand-outline"));
        tdAct.appendChild(actionButton("Edit", "edit", m, "btn-brand"));
        tdAct.appendChild(actionButton("Hapus", "delete", m, "btn-maroon"));
        tr.appendChild(tdAct);

        tbody.appendChild(tr);
      });

      countEl.textContent = "Menampilkan " + rows.length + " dari " + members.length + " anggota";
    }

    function openDetail(m) {
      var dl = document.getElementById("detail-body");
      dl.innerHTML = "";
      FIELDS.concat(["dibuat"]).forEach(function (f) {
        var dt = document.createElement("dt");
        var dd = document.createElement("dd");
        dt.className = "col-sm-4";
        dd.className = "col-sm-8";
        dt.textContent = LABELS[f];
        dd.textContent = f === "dibuat" ? formatDate(m[f]) : m[f];
        dl.appendChild(dt);
        dl.appendChild(dd);
      });
      modal(detailEl).show();
    }

    function openEdit(m) {
      FIELDS.forEach(function (f) { editForm.elements[f].value = m[f]; });
      showErrors(editForm, {});
      modal(editEl).show();
    }

    function openDelete(m) {
      document.getElementById("delete-name").textContent = m.nama;
      modal(deleteEl).show();
    }

    // Tombol Detail / Edit / Hapus pada tabel
    tbody.addEventListener("click", function (e) {
      var btn = e.target.closest("button[data-action]");
      if (!btn) return;
      var m = findMember(btn.getAttribute("data-id"));
      if (!m) return;
      currentId = m.id;
      var action = btn.getAttribute("data-action");
      if (action === "detail") openDetail(m);
      else if (action === "edit") openEdit(m);
      else openDelete(m);
    });

    // Simpan hasil edit
    editForm.addEventListener("submit", function (e) {
      e.preventDefault();
      var data = readForm(editForm);
      var errors = validate(data, currentId);
      showErrors(editForm, errors);
      if (Object.keys(errors).length) {
        toast("Periksa kembali isian formulir.", "error");
        return;
      }
      var updated = members.map(function (m) {
        return m.id === currentId ? Object.assign({}, m, data) : m;
      });
      if (!saveMembers(updated)) {
        toast("Gagal menyimpan perubahan.", "error");
        return;
      }
      members = updated;
      render();
      modal(editEl).hide();
      toast("Data anggota berhasil diperbarui.", "success");
    });

    // Konfirmasi hapus
    document.getElementById("confirm-delete").addEventListener("click", function () {
      var remaining = members.filter(function (m) { return m.id !== currentId; });
      if (!saveMembers(remaining)) {
        toast("Gagal menghapus data.", "error");
        return;
      }
      members = remaining;
      render();
      modal(deleteEl).hide();
      toast("Data anggota berhasil dihapus.", "success");
    });

    searchInput.addEventListener("input", render);

    // Tab lain mengubah data: muat ulang tabel
    window.addEventListener("storage", function (e) {
      if (e.key === STORAGE_KEY) {
        members = loadMembers();
        render();
      }
    });

    render();
  }

  /* ---------- Halaman Login ---------- */
  function initLogin() {
    var form = document.getElementById("login-form");
    var toggle = document.getElementById("toggle-password");
    clearOnInput(form);

    toggle.addEventListener("click", function () {
      var pw = form.elements.password;
      var show = pw.type === "password";
      pw.type = show ? "text" : "password";
      toggle.textContent = show ? "Sembunyikan" : "Tampilkan";
      toggle.setAttribute("aria-pressed", String(show));
    });

    form.addEventListener("submit", function (e) {
      e.preventDefault();
      var username = form.elements.username.value.trim();
      var password = form.elements.password.value;
      var errors = {};
      if (!username) errors.username = "Username wajib diisi.";
      if (!password) errors.password = "Kata sandi wajib diisi.";
      showErrors(form, errors);
      if (Object.keys(errors).length) return;

      if (username === DEMO_USER.username && password === DEMO_USER.password) {
        try { sessionStorage.setItem(SESSION_KEY, username); } catch (err) { /* abaikan */ }
        toast("Login berhasil. Mengalihkan ke daftar anggota…", "success");
        setTimeout(function () { window.location.href = "members.html"; }, 900);
      } else {
        toast("Username atau kata sandi salah.", "error");
        form.elements.password.value = "";
        form.elements.password.focus();
      }
    });
  }

  /* ---------- Mulai ---------- */
  initNavbar();
  var page = document.body.getAttribute("data-page");
  if (page === "registration") initRegistration();
  else if (page === "members") initMembers();
  else if (page === "login") initLogin();
})();