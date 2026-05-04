let loading_fontawesome = '<i class="fa fa-spinner fa-spin"></i>';

let get_form_data = ($form) => {
    let unindexed_array = $form.serializeArray();
    let indexed_array = {};
    $.map(unindexed_array, function(n, i){
        indexed_array[n['name']] = n['value'];
    });
    return indexed_array;
}

let add_commas = (nStr) =>{
    nStr += '';
    let x = nStr.split('.');
    let x1 = x[0];
    let x2 = x.length > 1 ? '.' + x[1] : '';
    let rgx = /(\d+)(\d{3})/;
    while (rgx.test(x1)) {
        x1 = x1.replace(rgx, '$1' + '.' + '$2');
    }
    return x1 + x2;
}

let remove_commas = (nStr) => {
    nStr = nStr.replace(/\./g,'');
    nStr = nStr.replace(/\,/g,'.');
    return nStr;
}

let remove_space = (nStr) => {
    nStr = nStr.replace(/\ /g,'');
    return nStr;
}

let format_date = (value) => {
    if (value !== '' && value !== null) {
        let data = value.split('-');
        return [data[2], data[1], data[0]].join('-');
    }
    return '';
}

let error_handle = (data) => {
    try {
        let errors = JSON.parse(data);
        errors = errors.errors;
        if (errors === undefined) console.log(data);
        $('.alert.alert-danger').addClass('d-none');
        $.each(errors, (i, value) => {
            $('#' + i + '_error').removeClass('d-none');
            $('#' + i + '_error_content').html(value.join(', '));
        });
    } catch (e) { }
}
let init_select2 = () => {
    const elements = document.querySelectorAll('[data-control="select2"], [data-kt-select2="true"]');
    elements.forEach( (element) => {
        const options = { dir: document.body.getAttribute("direction") };
        if (element.getAttribute("data-hide-search") === "true") options.minimumResultsForSearch = Infinity;
        $(element).select2(options);
    });
    $(document).on("select2:open", () => {
        const searchFields = document.querySelectorAll(".select2-container--open .select2-search__field");
        if (searchFields.length > 0) searchFields[searchFields.length - 1].focus();
    });
}
let init_form_element = () => {
    // init_select2();
    $('.datepicker').datepicker({
        autoclose: true,
        format: "dd-mm-yyyy",
        orientation: 'bottom',
        language:'id', todayHighlight:true
    })
    // $('.timepicker').flatpickr({
    //     enableTime: true,
    //     noCalendar: true,
    //     enableSeconds: true,
    //     dateFormat: "H:i:S",
    //     time_24hr: true,
    // });
    // $('.datepicker-full').flatpickr({
    //     enableTime: true,
    //     allowInput: true,
    //     enableSeconds: true,
    //     dateFormat: "Y-m-d H:i:S",
    //     time_24hr: true,
    // });
    $('.autonumeric')
        .attr('data-a-sep', '.')
        .attr('data-a-dec',',')
        .autoNumeric({mDec: '0', vMax:'9999999999999999999999999', vMin: '-99999999999999999'});
    $('.autonumeric-decimal')
        .attr('data-a-sep', '.')
        .attr('data-a-dec', ',')
        .autoNumeric({
            mDec: '2',
            vMax:'999999'
        });
    $(".only-numeric").on('keydown', (e) => {
        if ($.inArray(e.key, ["Backspace", "Delete", "Tab", "Escape", "Enter", "ArrowLeft", "ArrowRight"]) !== -1) {
            return;
        }
        if ((e.key >= "0" && e.key <= "9")) {
            return;
        }
        e.preventDefault();
    });
}
let get_location = ($target, tingkat, parent_id = '', selected = '', trigger_select2 = true) => {
    $.get(baseUrl + '/general/lokasi' + (parent_id !== '' ? ('/' + parent_id) : ''), (result) => {
        $target.html('');
        let caption = '';
        if (tingkat === 1) caption = 'Provinsi';
        if (tingkat === 2) caption = 'Kabupaten / Kota';
        if (tingkat === 3) caption = 'Kecamatan';
        if (tingkat === 4) caption = 'Desa / Kelurahan';
        $target.append('<option value="">-Pilih ' + caption + '-</option>');
        if (parent_id !== '' || tingkat === 1) {
            $.each(result, (i, value) => {
                let attr = value.id.toString().toLowerCase() === selected.toString().toLowerCase() ? 'selected' : '';
                $target.append('<option value="' + value.id + '" ' + attr + '>' + value.nama + '</option>');
            });
        }
        if (trigger_select2 === true) $target.change().select2();
        else $target.change();
    }).fail((xhr) => {
        console.log(xhr.responseText);
    });
}

let get_selected_page = (page, selected_page) => {
    let result = selected_page;
    if (page.toString() === '+1') result++;
    else if (page.toString() === '-1') result--;
    else result = page;
    return result;
}

let swal_delete_params = {
    title: 'Hapus Data ?',
    icon: 'question',
    showDenyButton: true,
    confirmButtonText: 'Hapus',
    denyButtonText: 'Batal',
    confirmButtonColor: '#F46A6A',
    denyButtonColor: '#bdbdbd'
}

let swal_restore_params = {
    title: 'Kembalikan Data ?',
    icon: 'question',
    showDenyButton: true,
    confirmButtonText: 'Ya',
    denyButtonText: 'Batal',
}


let preview_image = (event, target_preview) => {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = (e) => document.getElementById(target_preview).src = e.target.result;
        reader.readAsDataURL(file);
    }
}

let open_file = (target, target_preview) => {
    document.getElementById(target).addEventListener('change', (event) => preview_image(event, target_preview));
    $('#' + target).click();
};

let remove_file = (target, target_preview, default_url) => {
    console.log(target);
    document.getElementById(target).setAttribute('value', '1');
    document.getElementById(target_preview).src = default_url;
};

function str_unslug(value, separator = '-') {
    return value
        .replaceAll(separator, ' ')
        .toLowerCase()
        .replace(/\b\w/g, char => char.toUpperCase());
}

function str_slug(value, separator = '-') {
    return value.replaceAll(' ', separator).toLowerCase();
}

let setCookie = (name, value, days) => {
    const item = { value: value }; // Store the value

    if (days) {
        const now = new Date();
        item.expiry = now.getTime() + (days * 24 * 60 * 60 * 1000); // Calculate expiration time in milliseconds
    }

    localStorage.setItem(name, JSON.stringify(item)); // Store the value as a JSON string
}


let getCookie = (name) => {
    const itemStr = localStorage.getItem(name); // Get the stored item

    if (!itemStr) {
        return null; // If the item doesn't exist, return null
    }

    const item = JSON.parse(itemStr); // Parse the JSON string

    if (item.expiry) {
        const now = new Date();
        if (now.getTime() > item.expiry) {
            localStorage.removeItem(name); // If the item is expired, remove it
            return null; // Return null for expired items
        }
    }

    return item.value; // Return the stored value
}


let clearAnswerCookies = () => {
    for (let i = 0; i < localStorage.length; i++) {
        const key = localStorage.key(i); // Get each key from localStorage

        // Check if the key starts with "answer_"
        if (key && key.startsWith("answer_")) {
            localStorage.removeItem(key); // Remove the item from localStorage
        }
    }
}
