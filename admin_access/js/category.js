


document.addEventListener("DOMContentLoaded", function () {

    const rootDescription =
        document.getElementById("kd-root-description");

    const metaDescription =
        document.getElementById("kd-root-meta-description");


    /* =====================================================
       ROOT DESCRIPTION → META DESCRIPTION
    ===================================================== */

    if (rootDescription && metaDescription) {

        rootDescription.addEventListener("input", function () {

            metaDescription.value = this.value;

        });

    }

});

document.addEventListener("DOMContentLoaded", function () {

    const rootNameInput = document.getElementById("kd-root-name");
    const rootSlugInput = document.getElementById("kd-root-slug");
    const metaTitleInput = document.getElementById("kd-root-meta-title");
    const rootNameField = rootNameInput.closest(".kd-root-field");
    const rootNameError = document.getElementById("kd-root-name-error");


    rootNameInput.addEventListener("input", function () {

        const value = this.value;


        /* =====================================================
           ROOT NAME VALIDATION

           Apostrophe is allowed:
           Men's
           Women's
           Children's

           SQL safety is handled by PHP prepared statements.
        ===================================================== */

        /*
         * Allow:
         * Letters
         * Numbers
         * Spaces
         * Apostrophe
         */

        if (/[^a-zA-Z0-9 ']/.test(value)) {

            rootNameField.classList.add("kd-root-has-error");

            rootNameError.textContent =
                "Only letters, numbers, spaces and apostrophe (') are allowed.";

            this.setCustomValidity(
                "Invalid special character."
            );

        } else {

            rootNameField.classList.remove("kd-root-has-error");

            rootNameError.textContent = "";

            this.setCustomValidity("");
        }


        /* =====================================================
           AUTO SLUG
        ===================================================== */

        const cleanName = value
            .replace(/[^a-zA-Z0-9 ']/g, "")
            .trim();


        const slug = cleanName
            .toLowerCase()

            /* Remove apostrophe */
            .replace(/'/g, "")

            /* Spaces → hyphen */
            .replace(/\s+/g, "-")

            /* Multiple hyphens → single hyphen */
            .replace(/-+/g, "-")

            /* Remove hyphen from beginning/end */
            .replace(/^-+|-+$/g, "");


        rootSlugInput.value = slug;


        /* =====================================================
           AUTO META TITLE
        ===================================================== */

        metaTitleInput.value = cleanName;

    });

});

function show_add_categoryform() {
    let tableSection = document.getElementById("show_category_table");
    let formSection = document.getElementById("add_category_form");
    let btn = document.getElementById("add_cate_btn");
    let heading = document.getElementById("sectionHeading");

    // Agar table visible hai toh form dikhao, warna table dikhao
    if (!tableSection.classList.contains("hidden_class")) {
        tableSection.classList.add("hidden_class");
        formSection.classList.remove("hidden_class");
        btn.innerText = "Cencal";
        if (heading) heading.innerText = "Add New Category";
    } else {
        formSection.classList.add("hidden_class");
        tableSection.classList.remove("hidden_class");
        btn.innerText = "Add New";
        if (heading) heading.innerText = "Category Management";
    }
}


function doubleCheckDelete(rootId) {

    let firstConfirm = confirm(
        "Kya aap waqai is item ko delete karna chahte hain?"
    );

    if (!firstConfirm) {
        return;
    }

    let secondConfirm = confirm(
        "⚠️ Warning: Yeh action wapas nahi ho sakta! Kya aap 100% sure hain?"
    );

    if (!secondConfirm) {
        return;
    }

    const form = document.createElement("form");

    form.method = "POST";
    form.action = "";

    const actionInput = document.createElement("input");
    actionInput.type = "hidden";
    actionInput.name = "delete_category";
    actionInput.value = "1";

    const idInput = document.createElement("input");
    idInput.type = "hidden";
    idInput.name = "root_id";
    idInput.value = rootId;

    form.appendChild(actionInput);
    form.appendChild(idInput);

    document.body.appendChild(form);

    form.submit();
}

