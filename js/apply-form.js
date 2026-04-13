/**
 * apply-form.js
 * Handles all interactivity on apply.php:
 *   - File upload labels & photo preview
 *   - Beneficiary allocation total tracker
 *   - Monthly total auto-calculation
 *   - Payment method checkbox: show/hide payroll field
 *   - Scroll-based progress tracker
 *   - Form submission via fetch → saveapply.php
 *   - Success modal display
 */
(function () {
  "use strict";

  /* ── File upload label updates ──────────────────────────── */
  function initFileUploads() {
    var inputs = document.querySelectorAll(".af-upload-input");
    inputs.forEach(function (input) {
      input.addEventListener("change", function () {
        var item = this.closest(".af-upload-item");
        var nameEl = document.getElementById(this.id + "-name");
        var file = this.files[0];

        if (!file) {
          return;
        }

        if (nameEl) {
          nameEl.textContent = file.name;
        }
        if (item) {
          item.classList.add("has-file");
        }

        /* Photo preview */
        if (this.id === "doc-photo") {
          var preview = document.getElementById("photo-preview");
          var previewImg = document.getElementById("photo-preview-img");
          if (preview && previewImg) {
            var reader = new FileReader();
            reader.onload = function (e) {
              previewImg.src = e.target.result;
              preview.hidden = false;
            };
            reader.readAsDataURL(file);
          }
        }
      });
    });
  }

  /* ── Beneficiary rows: add / remove + allocation tracker ─── */
  function initBeneficiaryRows() {
    var tbody = document.getElementById("beneficiary-tbody");
    var addBtn = document.getElementById("ben-add-btn");
    var checkEl = document.getElementById("allocation-check");
    var display = document.getElementById("allocation-total");
    var warn = document.getElementById("allocation-warn");

    if (!tbody || !addBtn) {
      return;
    }

    /* Renumber all rows sequentially */
    function renumber() {
      var rows = tbody.querySelectorAll(".ben-row");
      rows.forEach(function (row, idx) {
        var numCell = row.querySelector(".ben-row__num");
        if (numCell) {
          numCell.textContent = idx + 1;
        }
      });
    }

    /* Recalculate allocation total */
    function updateAllocation() {
      var inputs = tbody.querySelectorAll(".ben-allocation");
      var total = 0;
      var hasAny = false;
      inputs.forEach(function (inp) {
        var v = parseFloat(inp.value) || 0;
        if (v > 0) {
          hasAny = true;
        }
        total += v;
      });
      if (!hasAny) {
        if (checkEl) {
          checkEl.hidden = true;
        }
        return;
      }
      if (checkEl) {
        checkEl.hidden = false;
      }
      if (display) {
        display.textContent = "Total: " + Math.round(total * 100) / 100 + "%";
      }
      if (warn) {
        if (Math.abs(total - 100) > 0.01) {
          warn.textContent = "⚠ Allocations must total exactly 100%";
          warn.style.color = "#b91c1c";
        } else {
          warn.textContent = "✓ Allocation is correct";
          warn.style.color = "#166534";
        }
      }
    }

    /* Bind remove button on a row */
    function bindRemove(row) {
      var btn = row.querySelector(".ben-remove-btn");
      if (!btn) {
        return;
      }
      btn.addEventListener("click", function () {
        row.remove();
        renumber();
        updateAllocation();
      });
    }

    /* Bind allocation input on a row */
    function bindAllocation(row) {
      var inp = row.querySelector(".ben-allocation");
      if (inp) {
        inp.addEventListener("input", updateAllocation);
      }
    }

    /* Bind existing rows */
    tbody.querySelectorAll(".ben-row").forEach(function (row) {
      bindRemove(row);
      bindAllocation(row);
    });

    /* Add new row */
    addBtn.addEventListener("click", function () {
      var existingRows = tbody.querySelectorAll(".ben-row");
      var newIdx = existingRows.length + 1;

      var tr = document.createElement("tr");
      tr.className = "ben-row ben-row--new";
      tr.innerHTML =
        '<td class="af-table__num ben-row__num">' +
        newIdx +
        "</td>" +
        '<td><input type="text"   name="ben_name[]"         class="af-input af-input--table ben-name"></td>' +
        '<td><input type="text"   name="ben_relationship[]" class="af-input af-input--table ben-relationship" placeholder="e.g. Sibling"></td>' +
        '<td><input type="number" name="ben_allocation[]"   class="af-input af-input--table ben-allocation" min="0" max="100" placeholder="%"></td>' +
        '<td><input type="text"   name="ben_idno[]"         class="af-input af-input--table"></td>' +
        '<td><input type="tel"    name="ben_mobile[]"       class="af-input af-input--table" placeholder="+254…"></td>' +
        '<td class="af-table__action">' +
        '<button type="button" class="ben-remove-btn" aria-label="Remove this beneficiary">' +
        '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" width="14" height="14" aria-hidden="true"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>' +
        "</button>" +
        "</td>";

      tbody.appendChild(tr);
      bindRemove(tr);
      bindAllocation(tr);

      /* Focus the name field of the new row */
      var nameInput = tr.querySelector(".ben-name");
      if (nameInput) {
        nameInput.focus();
      }
    });
  }

  /* ── Monthly total auto-calculation ─────────────────────── */
  function initTotalCalc() {
    var capitalInput = document.getElementById("capital_shares");
    var savingsInput = document.getElementById("savings_total");
    var totalInput = document.getElementById("total_monthly");
    var savingsFields = document.querySelectorAll(".af-savings-input");

    /* Auto-sum savings sub-fields into savings_total */
    savingsFields.forEach(function (f) {
      f.addEventListener("input", function () {
        var sum = 0;
        savingsFields.forEach(function (sf) {
          sum += parseFloat(sf.value) || 0;
        });
        if (savingsInput) {
          savingsInput.value = sum || "";
        }
        calcTotal();
      });
    });

    function calcTotal() {
      var cap = parseFloat(capitalInput && capitalInput.value) || 0;
      var sav = parseFloat(savingsInput && savingsInput.value) || 0;
      if (totalInput) {
        totalInput.value = cap + sav || "";
      }
    }

    if (capitalInput) {
      capitalInput.addEventListener("input", calcTotal);
    }
    if (savingsInput) {
      savingsInput.addEventListener("input", calcTotal);
    }
  }

  /* ── Payroll field toggle ────────────────────────────────── */
  function initPayrollToggle() {
    var checkoff = document.getElementById("pm-checkoff");
    var payrollRow = document.getElementById("payroll-row");

    if (!checkoff || !payrollRow) {
      return;
    }

    function toggle() {
      payrollRow.classList.toggle("is-visible", checkoff.checked);
    }

    checkoff.addEventListener("change", toggle);
    toggle();
  }

  /* ── Progress tracker ────────────────────────────────────── */
  function initProgressTracker() {
    var items = document.querySelectorAll(".apply-progress__item");
    if (!items.length) {
      return;
    }

    /*
     * Each key maps to one element ID OR an array of element IDs.
     * 'consent' covers the photo consent table, newsletter opt-ins,
     * AND the declaration — all three are separate fieldsets but
     * share one progress item.
     */
    var sectionMap = {
      member: ["section-member"],
      employment: ["section-employment"],
      bank: ["section-bank"],
      kin: ["section-kin"],
      beneficiary: ["section-beneficiary"],
      remittances: ["section-remittances"],
      consent: ["section-consent", "section-newsletter", "section-declaration"],
      documents: ["section-documents"],
    };

    function elementHasInput(el) {
      if (!el) {
        return false;
      }

      /* Text / select / textarea */
      var inputs = el.querySelectorAll(
        'input:not([type="checkbox"]):not([type="radio"]):not([type="file"]), select, textarea',
      );
      for (var i = 0; i < inputs.length; i++) {
        if (inputs[i].value.trim() !== "") {
          return true;
        }
      }

      /* File inputs */
      var files = el.querySelectorAll('input[type="file"]');
      for (var j = 0; j < files.length; j++) {
        if (files[j].files && files[j].files.length > 0) {
          return true;
        }
      }

      /* Checkboxes */
      var checkboxes = el.querySelectorAll('input[type="checkbox"]');
      for (var k = 0; k < checkboxes.length; k++) {
        if (checkboxes[k].checked) {
          return true;
        }
      }

      /* Radio buttons */
      var radios = el.querySelectorAll('input[type="radio"]');
      for (var r = 0; r < radios.length; r++) {
        if (radios[r].checked) {
          return true;
        }
      }

      return false;
    }

    function sectionHasInput(ids) {
      for (var i = 0; i < ids.length; i++) {
        var el = document.getElementById(ids[i]);
        if (elementHasInput(el)) {
          return true;
        }
      }
      return false;
    }

    function update() {
      items.forEach(function (item) {
        var key = item.getAttribute("data-section");
        var ids = sectionMap[key] || [];
        item.classList.toggle("is-done", sectionHasInput(ids));
      });
    }

    document.addEventListener("input", update);
    document.addEventListener("change", update);
    update();
  }

  /* ── Form submission ─────────────────────────────────────── */
  function initFormSubmit() {
    var form = document.getElementById("apply-form");
    var submitBtn = document.getElementById("apply-submit");
    var errEl = document.getElementById("apply-error");
    var dupEl = document.getElementById("apply-duplicate");
    var modal = document.getElementById("success-modal");

    if (!form) {
      return;
    }

    form.addEventListener("submit", function (e) {
      e.preventDefault();

      /* ── Validation: find first invalid field ─────────── */
      var required = form.querySelectorAll("[required]");
      var firstInvalid = null;
      var missingLabels = [];
      var seenRadioGroups = {};

      required.forEach(function (f) {
        var invalid = false;

        if (f.type === "radio") {
          if (seenRadioGroups[f.name]) {
            return;
          }
          seenRadioGroups[f.name] = true;
          var anyChecked = false;
          form
            .querySelectorAll('input[name="' + f.name + '"]')
            .forEach(function (r) {
              if (r.checked) {
                anyChecked = true;
              }
            });
          if (!anyChecked) {
            invalid = true;
          }
        } else if (f.type === "checkbox") {
          if (!f.checked) {
            invalid = true;
          }
        } else if (f.type === "file") {
          if (!f.files || f.files.length === 0) {
            invalid = true;
          }
        } else {
          if (!f.value.trim()) {
            invalid = true;
          }
        }

        if (invalid) {
          if (!firstInvalid) {
            firstInvalid = f;
          }
          var legend =
            f.closest("fieldset") &&
            f.closest("fieldset").querySelector("legend");
          var label = f.previousElementSibling;
          var text =
            (label && label.textContent) ||
            (legend && legend.textContent) ||
            f.name;
          missingLabels.push(text.replace(/\s+/g, " ").trim().slice(0, 50));
        }
      });

      if (missingLabels.length) {
        if (errEl) {
          errEl.textContent =
            "Please complete all required fields. Missing: " +
            missingLabels.slice(0, 3).join(", ") +
            (missingLabels.length > 3 ? "…" : ".");
          errEl.hidden = false;
          errEl.removeAttribute("hidden");
        }
        /* FIX 3: scroll to the first problem, not the error banner */
        if (firstInvalid) {
          firstInvalid.scrollIntoView({ behavior: "smooth", block: "center" });
          firstInvalid.classList.add("af-input--highlight");
          setTimeout(function () {
            firstInvalid.classList.remove("af-input--highlight");
          }, 2500);
          firstInvalid.focus();
        }
        return;
      }

      submitBtn.disabled = true;
      submitBtn.innerHTML = "Submitting…";
      hide(errEl);
      hide(dupEl);

      var formData = new FormData(form);

      /*
       * Read response as plain text first, then parse JSON manually.
       * This means PHP notices/warnings prepended to the JSON body
       * produce a clear server-error message rather than a cryptic
       * "network error", and true network failures are separately caught.
       */
      fetch("saveapply.php", { method: "POST", body: formData })
        .then(function (res) {
          return res.text();
        })
        .then(function (txt) {
          var data;
          try {
            /* If PHP output any notices before the JSON, skip past them */
            var start = txt.indexOf("{");
            data = JSON.parse(start >= 0 ? txt.slice(start) : txt);
          } catch (e) {
            console.error("Non-JSON server response:", txt.slice(0, 500));
            showError(
              errEl,
              "The server returned an unexpected response. " +
                "Please try again or contact us on +254 724 053 548.",
            );
            resetBtn();
            return;
          }

          if (data.status === "ok") {
            showModal(data.ref, data.email, data.print_url);
          } else if (data.status === "duplicate") {
            show(dupEl);
            dupEl.scrollIntoView({ behavior: "smooth", block: "center" });
            resetBtn();
          } else {
            showError(
              errEl,
              data.message || "An error occurred. Please try again.",
            );
            resetBtn();
          }
        })
        .catch(function (err) {
          /* Only fires on a genuine network failure */
          console.error("Network failure:", err);
          showError(
            errEl,
            "Could not reach the server. Please check your connection and try again, " +
              "or contact us on +254 724 053 548.",
          );
          resetBtn();
        });
    });

    function resetBtn() {
      submitBtn.disabled = false;
      submitBtn.innerHTML = "Submit Application";
    }

    function showModal(ref, email, printUrl) {
      var modalRef = document.getElementById("modal-ref");
      var modalEmail = document.getElementById("modal-email");
      var modalDl = document.getElementById("modal-download");

      if (modalRef) {
        modalRef.textContent = ref;
      }
      if (modalEmail) {
        modalEmail.textContent = email;
      }
      if (modalDl) {
        modalDl.href = printUrl;
      }

      if (modal) {
        modal.hidden = false;
        modal.removeAttribute("hidden");
        document.body.style.overflow = "hidden";
      }
    }
  }

  /* ── Helpers ─────────────────────────────────────────────── */
  function showError(el, msg) {
    if (!el) {
      return;
    }
    el.textContent = msg;
    el.hidden = false;
    el.removeAttribute("hidden");
    el.scrollIntoView({ behavior: "smooth", block: "nearest" });
  }

  function show(el) {
    if (el) {
      el.hidden = false;
      el.removeAttribute("hidden");
    }
  }
  function hide(el) {
    if (el) {
      el.hidden = true;
    }
  }

  /* ── Boot ────────────────────────────────────────────────── */
  document.addEventListener("DOMContentLoaded", function () {
    initFileUploads();
    initBeneficiaryRows();
    initTotalCalc();
    initPayrollToggle();
    initProgressTracker();
    initFormSubmit();
  });
})();
