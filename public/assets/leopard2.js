function onSubmit(form, button, defaultClass = true) {
  const elementForm = document.getElementById(form);
  const elementButton = document.getElementById(button);

  elementForm.addEventListener("submit", function () {
    elementButton.setAttribute("disabled", true);

    if (defaultClass) {
      elementButton.innerHTML =
        '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span><span> Enviando Informações...</span>';
    }
  });
}

function categories() {
  const elements = {
    word: document.getElementById("words"),
    result: document.getElementById("results"),
    button: document.getElementById("create-btn"),
    title: document.getElementById("title"),
    field: document.getElementById("custom-fields"),
    block: document.getElementById("custom-fields-block"),
    attachment: document.getElementById("attachment"),
    attachmentLabel: document.querySelector("label[for='attachment']"),
    attachmentSmall: document.querySelector("label[for='attachment'] > small"),
  };

  let timeout;

  elements.word.addEventListener("input", function () {
    clearTimeout(timeout);

    timeout = setTimeout(() => {
      fetch("/helpdesk/request/type/category", {
        method: "POST",
        body: JSON.stringify({ words: this.value }),
        mode: "cors",
        headers: { "Content-Type": "application/json" },
      })
        .then((response) => response.json())
        .then((categories) => {
          if (categories.result === false) {
            elements.result.innerHTML = `
                <div class="message empty">
                  <div class="header">Ops!</div>
                  <div class="description">${categories.message}</div>
                </div>`;
            elements.result.style.display = "block";
            elements.button.setAttribute("disabled", true);
            return;
          }

          elements.result.style.display = "block";
          let view = '<div class="visible">';

          categories.items.forEach((element) => {
            view += `
                <div class="category">
                  <div class="name">${element.departament}</div>
                  <div class="results visible">
                    <a class="result text-reset text-decoration-none item" 
                       data-category="${element.category_name}" 
                       data-subcategory="${element.sub_category}" 
                       data-is-required-attachment="${element.is_required_attachment}">
                      <div class="content">
                        <div class="title">${element.category_name}</div>
                        <div class="description">${element.category_description}</div>
                      </div>
                    </a>
                  </div>
                </div>`;
          });

          view += "</div>";
          elements.result.innerHTML = view;

          document.querySelectorAll(".item").forEach((item) => {
            item.addEventListener("click", function () {
              const category = this.getAttribute("data-category");
              const subcategory = this.getAttribute("data-subcategory");
              const isRequiredAttachment = this.getAttribute(
                "data-is-required-attachment"
              );

              const hidden = document.createElement("input");
              hidden.type = "hidden";
              hidden.name = "subcategory";
              hidden.value = subcategory;
              elements.block.appendChild(hidden);

              if (isRequiredAttachment === "S") {
                elements.attachment.setAttribute("required", true);
                elements.attachmentLabel.classList.add("required");
                elements.attachmentSmall.textContent = "(obrigatório)";
              } else {
                elements.attachment.removeAttribute("required");
                elements.attachmentLabel.classList.remove("required");
                elements.attachmentSmall.textContent = "(opcional)";
              }

              fetch("/helpdesk/request/type/fields", {
                method: "POST",
                body: JSON.stringify({ id: subcategory }),
                mode: "cors",
                headers: { "Content-Type": "application/json" },
              })
                .then((response) => response.json())
                .then((fields) => {
                  elements.field.innerHTML = "";

                  if (fields.result === false) {
                    elements.block.classList.add("d-none");
                    return;
                  }

                  fields.fields.forEach((field) => {
                    const div = document.createElement("div");
                    div.className = "form-group mb-2";

                    const input = document.createElement("input");
                    input.type = "text";
                    input.name = field.field_name;
                    input.id = field.field_name;
                    input.autocomplete = "off";
                    input.className = "form-control";

                    if (field.field_required) {
                      input.setAttribute("required", true);
                    }

                    const label = document.createElement("label");
                    label.htmlFor = field.field_name;
                    label.classList.add("form-label");
                    label.innerHTML = field.field_description + ":";

                    if (field.field_required) {
                      label.classList.add("required");
                    }

                    div.appendChild(label);
                    div.appendChild(input);
                    elements.field.appendChild(div);
                  });

                  elements.block.classList.remove("d-none");
                });

              elements.word.value = category;
              elements.title.value = category;
              elements.result.style.display = "none";
            });
          });

          elements.button.removeAttribute("disabled");
        });
    }, 500);
  });
}

function wordCount(field, div, max = 3000) {
  const count = document.getElementById(field);

  count.addEventListener("keyup", function () {
    let value = count.value.length;
    return (div.innerHTML = max - value);
  });
}

function toggleMenu(event) {
  if (event.type === "touchstart") {
    event.preventDefault();
  }

  const nav = document.getElementById("nav");

  nav.classList.toggle("active");

  const active = nav.classList.contains("active");

  event.currentTarget.setAttribute("aria-expanded", active);

  if (active) {
    event.currentTarget.setAttribute("aria-label", "Abrir Menu");
  } else {
    event.currentTarget.setAttribute("aria-label", "Fechar Menu");
  }
}

function execToggle() {
  const btn_mobile = document.getElementById("mobile-btn");

  btn_mobile.addEventListener("click", toggleMenu);
  btn_mobile.addEventListener("touchstart", toggleMenu);
}

function employee(id) {
  const entity = document.getElementById(id);

  entity.addEventListener("blur", function () {
    fetch("/helpdesk/request/type/entity", {
      method: "POST",
      body: JSON.stringify({
        entity: this.value,
      }),
      mode: "cors",
      headers: { "Content-type": "application/x-www-form-urlencoded" },
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.result === false) {
          window.alert(data.message);
          this.value = "";

          return;
        }

        this.type = "text";
        this.value = `${data.entity}  ${data.name}`;
      });
  });
}
