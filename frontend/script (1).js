$(document).ready(function() {
    var token = '';

    // URL API
    var loginUrl = 'http://127.0.0.1:8000/api/login';
    var dataUrl  = 'http://127.0.0.1:1000/api/admin/products';
    var ImageUrl = 'http://127.0.0.1:1000/storage/';

    // Fungsi untuk menangani kesalahan
    function handleError(jqXHR, textStatus, errorThrown) {
        console.error('Error: ' + textStatus, errorThrown);
        $('#dataResult').text('Error: ' + textStatus + ' ' + errorThrown);
    }

    // Login request
    // login.js
    $(document).ready(function() {
    $('#loginForm').on('submit', function(e) {
        e.preventDefault();
        $('#login-error').text(''); // Mengosongkan pesan error sebelumnya
 
        var loginData = {
            email: $('#email').val(),
            password: $('#password').val()
        };
 
        $.ajax({
            url: 'http://127.0.0.1:8000/api/login',
            method: 'POST',
            contentType: 'application/json',
            data: JSON.stringify(loginData),
            dataType: 'json',
            success: function(response) {
                var token = response.access_token;
                $('#loginResult').text('Login berhasil! Token: ' + token);
                console.log('Login berhasil:', response);
                localStorage.setItem('token', token);
                window.location.href = 'halaman.html';
            },
            error: function(xhr, status, error) {
                $('#login-error').text(xhr.responseJSON.error); // Menampilkan pesan error di atas tombol login
                console.error('Login gagal:', xhr.responseJSON.error);
                $('#loginResult').text('Login Gagal'); // Mengosongkan pesan hasil login
                $('#email').val(''); // Mengosongkan input email
                $('#password').val(''); // Mengosongkan input password
            }
        });
    });
});
    

    // Fungsi untuk membuat tabel
    function createTable(data) {
        var table = $('<table class="table table-bordered"></table>');
        var thead = $('<thead><tr></tr></thead>');
        var tbody = $('<tbody></tbody>');

        // Buat header tabel
        Object.keys(data[0]).forEach(function(key) {
            thead.find('tr').append('<th>' + key + '</th>');
        });

        // Buat baris data
        data.forEach(function(item) {
            var row = $('<tr></tr>');
            Object.entries(item).forEach(function([key, value]) {
                if (key === 'image' && value !== '') {
                    row.append('<td>image:<img src="'+ ImageUrl + value + '" alt="Image" style="max-width:100px; max-height:100px;"></td>');
                } else {
                    row.append('<td>' + value + '</td>');
                }
            });
            tbody.append(row);
        });

        table.append(thead).append(tbody);
        return table;
    }

    // Fungsi untuk membuat kartu
function createCards(data) {
    var cards = $('<div class="card-deck"></div>');
    data.forEach(function(item) {
        var card = $('<div class="card"></div>');
        var cardBody = $('<div class="card-body"></div>');

        // Menampilkan properti data sebagai teks atau gambar
        Object.entries(item).forEach(function([key, value]) {
            if (key === 'image' && value !== '') {
                cardBody.append('<p class="card-text"><strong>' + key + ':</strong> <img src="'+ ImageUrl + value + '" class="card-img-top" alt="Image">');
            } else {
                cardBody.append('<p class="card-text"><strong>' + key + ':</strong> ' + value + '</p>');
            }
        });

        card.append(cardBody);
        cards.append(card);
    });

    return cards;
}

    // Fetch data request
    $('#fetchData').click(function() {
        if (!token) {
            $('#dataResult').text('You must login first!');
            return;
        }

        $.ajax({
            url: dataUrl,
            type: 'GET',
            headers: {
                'Authorization': 'Bearer ' + token
            },
            dataType: 'json',
            success: function(data) {
                $('#dataResult').empty(); // Kosongkan hasil sebelumnya
                
                if (data.success) {
                    // Menampilkan data dalam bentuk tabel
                    // $('#dataResult').append(createTable(data));

                    // Menampilkan data dalam bentuk kartu
                    $('#dataResult').append(createTable(data.data.products));
                } else {
                    $('#dataResult').text('Data fetched is not an array.');
                }
            },
            error: handleError
        });
    });
});
