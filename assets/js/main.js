setTimeout(function () {
  var notification = document.getElementById('notification')
  if (notification) {
    notification.style.display = 'none'
  }
}, 7000)

document.addEventListener('DOMContentLoaded', () => {
  const selects = document.querySelectorAll('select')

  selects.forEach(select => {
    select.style.outline = 'none'
    select.style.border = '1px solid #ced4da'
    select.style.boxShadow = 'none !important'

    select.addEventListener('focus', () => {
      select.style.borderColor = '#80bdff'
      select.style.boxShadow = 'none !important'
    })

    select.addEventListener('blur', () => {
      select.style.borderColor = '#ced4da'
    })
  })
})

document.querySelectorAll('.form-control').forEach(input => {
  let autocompleteValue = 'off'; // Default value

  // Menetapkan nilai autocomplete berdasarkan ID
  if (input.id) {
    switch (input.id) {
      case 'name':
      case 'full-name':
        autocompleteValue = 'name';
        break;
      case 'email':
        autocompleteValue = 'email';
        break;
      case 'phone':
      case 'telephone':
        autocompleteValue = 'tel';
        break;
      case 'address':
      case 'street-address':
        autocompleteValue = 'address-line1';
        break;
      case 'address2':
      case 'street-address2':
        autocompleteValue = 'address-line2';
        break;
      case 'city':
        autocompleteValue = 'address-level2';
        break;
      case 'postal-code':
        autocompleteValue = 'postal-code';
        break;
      case 'country':
        autocompleteValue = 'country';
        break;
      default:
        autocompleteValue = 'off'; // Nilai default jika ID tidak cocok
        break;
    }
  } else if (input.name) {
    switch (input.name) {
      case 'name':
      case 'fullName':
        autocompleteValue = 'name';
        break;
      case 'email':
        autocompleteValue = 'email';
        break;
      case 'phone':
      case 'telephone':
        autocompleteValue = 'tel';
        break;
      case 'address':
      case 'streetAddress':
        autocompleteValue = 'address-line1';
        break;
      case 'address2':
      case 'streetAddress2':
        autocompleteValue = 'address-line2';
        break;
      case 'city':
        autocompleteValue = 'address-level2';
        break;
      case 'postalCode':
        autocompleteValue = 'postal-code';
        break;
      case 'country':
        autocompleteValue = 'country';
        break;
      default:
        autocompleteValue = 'off'; // Nilai default jika nama tidak cocok
        break;
    }
  }

  // Setel atribut autocomplete
  input.setAttribute('autocomplete', autocompleteValue);

  // Hapus box-shadow saat fokus
  input.addEventListener('focus', () => {
    input.style.setProperty('box-shadow', 'none', 'important');
  });
});

document.querySelectorAll('.form-select').forEach(select => {
  select.style.setProperty('box-shadow', 'none', 'important')
  select.style.setProperty('outline', 'none', 'important')
  select.style.setProperty('cursor', 'pointer', 'important')

  select.addEventListener('focus', () => {
    select.style.setProperty('box-shadow', 'none', 'important')
  })

  Array.from(select.options).forEach(option => {
    option.style.setProperty('cursor', 'pointer', 'important')
  })
})

const links = document.querySelectorAll('.nav-link')
links.forEach(link => {
  link.addEventListener('click', () => {
    links.forEach(l => l.classList.remove('active'))
    link.classList.add('active')
  })
})

console.log(
  'Aplikasi ini dirancang dan dikembangkan oleh Jagad Brahma Wiraataja menggunakan berbagai teknologi web terbaru untuk memastikan performa dan pengalaman pengguna yang optimal. Untuk melihat lebih banyak proyek dan kode sumber yang kami kerjakan, silakan kunjungi profil GitHub kami di https://github.com/idugeni. Terima kasih telah mengunjungi!'
)

document.addEventListener('DOMContentLoaded', function () {
  // Ambil semua elemen gambar di halaman
  const images = document.querySelectorAll('img')

  images.forEach(img => {
    // Tambahkan atribut lazy loading jika belum ada
    if (!img.hasAttribute('loading')) {
      img.setAttribute('loading', 'lazy')
    }
  })
})