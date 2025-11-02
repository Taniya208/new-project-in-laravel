<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Connectify | My Contacts</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(135deg, #43cea2, #185a9d);
      min-height: 100vh;
      color: #fff;
      font-family: 'Poppins', sans-serif;
    }
    .card {
      background: rgba(255,255,255,0.12);
      border: none;
      border-radius: 12px;
      backdrop-filter: blur(10px);
    }
    .btn-add { background: #ff7eb3; border: none; }
    .btn-add:hover { background: #ff4f81; }
  </style>
</head>

<body>
  <div class="container py-4">
    <!-- Logout -->
    <form action="{{ route('logout') }}" method="POST" class="float-end">
      @csrf
      <button class="btn btn-danger">Logout</button>
    </form>

    <h2 class="fw-bold mb-4">My Contacts</h2>

    <!-- Add / Update Contact -->
    <div class="card p-3 mb-3">
      <form id="contactForm" class="d-flex gap-2 flex-wrap">
        @csrf
        <input name="name" id="name" class="form-control" placeholder="Name" required>
        <input name="email" id="email" class="form-control" placeholder="Email" required>
        <input name="description" id="description" class="form-control" placeholder="Description">
        <button type="submit" id="saveBtn" class="btn btn-add text-white">Save</button>
      </form>
      <div id="formErrors" class="mt-2"></div>
    </div>

    <!-- Contact Table -->
    <div class="card p-3">
      <table class="table table-bordered text-center text-white" id="contactsTable">
        <thead>
          <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Description</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody></tbody>
      </table>
    </div>
  </div>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

  <script>
    // 🛡️ Set CSRF token for all AJAX requests
    $.ajaxSetup({
      headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });

    function showErrors(errors) {
      let html = '<div class="alert alert-danger">';
      if (typeof errors === 'string') { html += errors; }
      else {
        Object.keys(errors).forEach(k => {
          html += '<div>' + errors[k].join(', ') + '</div>';
        });
      }
      html += '</div>';
      $('#formErrors').html(html);
    }

    // 🔄 Load all contacts
    function loadContacts() {
      $.get("{{ url('/contacts/fetch') }}", function(res) {
        let rows = '';
        if (res.contacts && res.contacts.length) {
          res.contacts.forEach(c => {
            rows += `
              <tr>
                <td>${c.id}</td>
                <td>${c.name}</td>
                <td>${c.email}</td>
                <td>${c.description ?? ''}</td>
                <td>
                  <button class="btn btn-warning btn-sm btn-edit"
                    data-id="${c.id}"
                    data-name="${c.name}"
                    data-email="${c.email}"
                    data-description="${c.description ?? ''}">
                    Edit
                  </button>
                  <button class="btn btn-danger btn-sm deleteBtn" data-id="${c.id}">
                    Delete
                  </button>
                </td>
              </tr>
            `;
          });
        } else {
          rows = `<tr><td colspan="5" class="text-muted">No contacts found</td></tr>`;
        }
        $('#contactsTable tbody').html(rows);
      }).fail(function(xhr) {
        console.error(xhr.responseText);
        alert('Could not load contacts. Check console or server logs.');
      });
    }

    $(document).ready(function() {
      loadContacts(); // Initial load

      // 💾 Save or Update Contact
      $('#contactForm').on('submit', function(e) {
        e.preventDefault();
        $('#formErrors').html('');

        const id = $('#saveBtn').data('id');
        const url = id ? `/contacts/${id}` : "{{ route('contacts.store') }}";
        const method = id ? 'PUT' : 'POST';

        $.ajax({
          url: url,
          method: method,
          data: $(this).serialize(),
          success: function(res) {
            alert(res.message || 'Contact saved successfully!');
            $('#contactForm')[0].reset();
            $('#saveBtn').text('Save').removeData('id');
            loadContacts();
          },
          error: function(xhr) {
            if (xhr.status === 422 && xhr.responseJSON?.errors) {
              showErrors(xhr.responseJSON.errors);
            } else {
              console.error(xhr.responseText);
              showErrors('Server error. Please check logs.');
            }
          }
        });
      });

      // ✏️ Edit Contact (load data into form)
      $(document).on('click', '.btn-edit', function() {
        const id = $(this).data('id');
        $('#name').val($(this).data('name'));
        $('#email').val($(this).data('email'));
        $('#description').val($(this).data('description'));
        $('#saveBtn').text('Update').data('id', id);
      });

      // 🗑️ Delete Contact
      $(document).on('click', '.deleteBtn', function() {
        const id = $(this).data('id');
        if (!confirm('Are you sure you want to delete this contact?')) return;

        $.ajax({
          url: "{{ url('/contacts') }}/" + id,
          method: 'DELETE',
          success: function(res) {
            alert(res.message || 'Contact deleted successfully!');
            loadContacts();
          },
          error: function(xhr) {
            console.error(xhr.responseText);
            alert('Could not delete contact. Check console for details.');
          }
        });
      });
    });
  </script>
</body>
</html>
