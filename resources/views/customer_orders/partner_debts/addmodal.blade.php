<!-- Add New Customer modal -->
<div class="modal fade" id="{{$id}}" tabindex="-1" aria-labelledby="vertical-center-modal"
    aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header d-flex align-items-center">
                <h4 class="modal-title" id="myLargeModalLabel">
                    Pembayaran ke Partner
                </h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('customer-order.partner.pay', ['id' => $codId, 'debt_id' => $debtId]) }}" method="POST" class="row" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-4">
                        <div id="partner-placeholder-image"
                           class="d-flex p-5 text-center align-items-center justify-content-center rounded-5 border-2 border-dashed"
                           style="max-height:20em !important;">
                           <div>
                              <div class="fs-4">If Image Selected, it will show (Preview)</div>
                           </div>
                        </div>
                        <img src="" id="partner-preview-image" alt="Product Image Preview" class="d-none rounded-4 shadow w-100"
                           style="max-height:20em !important; object-fit:contain;">
                     </div>

                     <div class="mb-4">
                        <label class="form-label fw-semibold">Bukti Bayar ke Partner</label>
                        <div class="input-group">
                           <span class="input-group-text px-6" id="basic-addon1"><i
                                    class="ti ti-photo fs-6"></i></span>
                           <input type="file" name="partner-image" class="form-control ps-2">
                        </div>
                     </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Deskripsi</label>
                        <div class="input-group">
                            <span class="input-group-text px-6" id="basic-addon1"><i
                                    class="ti ti-align-justified fs-6"></i></span>
                            <textarea class="form-control ps-2" name="partner-description" id="partner-description" cols="20" rows="5"
                                placeholder="Description about this Customer">{{ old('partner-description') }}</textarea>
                        </div>
                    </div>

                    <div class="d-flex gap-1 align-items-center justify-content-end">
                        <button type="button" class="btn bg-danger-subtle text-danger  waves-effect text-start"
                            data-bs-dismiss="modal">
                            Close
                        </button>
                        <button type="submit" class="btn btn-primary btn-al-primary">Simpan Pembayaran ke Partner</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('change', function(event) {
   // Cek apakah event berasal dari input file yang memiliki atribut name="image"
   if (event.target.matches('input[type="file"][name="partner-image"]')) {
      const fileInput = event.target;
      const formContainer = fileInput.closest('.row'); // Mencari form terkait dalam satu grup

      if (!formContainer) return;

      const placeholder = formContainer.querySelector('#partner-placeholder-image');
      const previewImage = formContainer.querySelector('#partner-preview-image');

      const file = fileInput.files[0];

      if (file) {
         if (file.type.startsWith('image/')) {
               // Preview image
               const reader = new FileReader();
               reader.onload = function(e) {
                  previewImage.src = e.target.result;
                  previewImage.classList.remove('d-none'); // Show image preview
                  placeholder.classList.add('d-none'); // Hide placeholder
               };
               reader.readAsDataURL(file);
         } else {
               // Preview file
               previewImage.classList.add('d-none'); // Hide image preview
               placeholder.classList.add('d-none'); // Hide placeholder
         }
      } else {
         // Reset previews
         previewImage.src = '';
         previewImage.classList.add('d-none');
         placeholder.classList.remove('d-none'); // Show placeholder
      }
   }
});
</script>
