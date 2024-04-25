<form action="" method="GET" id="formCariProduk">
    <div class="input-group">
        <input type="text" class="form-control" placeholder="Nama Product" id="searchProduk">
        <div class="input-group-append">
            <button class="btn btn-primary" type="submit">
                Cari
            </button>
        </div>
    </div>    
</form>
<table class="table table-stripped table-hover table-sm mt-3">
    <thead>
        <tr>
            <th colspan="2" class="border-0">Hasil Pencarian : </th>
        </tr>
    </thead>
    <tbody id="resultProduk"></tbody>
</table>

@push('scripts')
    <script>
        $('#formCariProduk').submit(function(event) {
            event.preventDefault();
            const search = $('#searchProduk').val();
            // console.log(search);
            
            if (search.length >= 3) {
                fetchCariProduk(search);
            }
        })

        function fetchCariProduk(search) {
            $.getJSON("/transaksi/produk", {
                search: search
            },
            function(response) {
                $('#resultProduk').html('')

                if (response.length > 0) {
                    response.forEach(item => {
                        addResultProduct(item);
                    });
                } else {
                    const row = `<tr>
                            <td class="text-center text-danger font-weight-bold">Produk tidak ditemukan</td>
                        </tr>`;
                    $('#resultProduk').append(row);
                }

            });
        }

        function addResultProduct(item) {
            const {
                nama_produk,
                kode_produk,
                stok
            } = item

            const btn = `<button type="button" class="btn btn-xs btn-success" ${stok <= 0 ? 'disabled' : ''} onclick="addItem('${kode_produk}')">
                ${stok <= 0 ? 'Produk kosong' : 'Add'} 
                </button>`;

            const row = `<tr>
                    <td>${nama_produk}</td>
                    <td class="text-right">${btn}</td>
                </tr>`;
            $('#resultProduk').append(row);
        }
    </script>
@endpush