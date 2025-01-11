$(document).ready(function () {
  const $result = $("#result");
  const $feedback = $("#feedback");
  const $search = $("#search");
  let timer;

  function loadData(page = 1, query = "") {
    $.ajax({
      url: "get.php",
      method: "POST",
      data: { page: page, search: query },
      success: function (response) {
        $result.html(response);
      },
      error: function () {
        showFeedback("error", "Failed to load student data.");
      },
    });
  }

  function showFeedback(type, message) {
    $feedback
      .removeClass("alert-success alert-danger")
      .addClass(type === "success" ? "alert-success" : "alert-danger")
      .text(message)
      .show();

    setTimeout(() => $feedback.fadeOut(), 2000);
  }

  loadData();

  $search.keyup(function () {
    clearTimeout(timer);
    const query = $(this).val();
    timer = setTimeout(() => loadData(1, query), 500);
  });

  $(document).on("click", ".page-link", function (e) {
    e.preventDefault();
    const page = $(this).data("page");
    const query = $search.val();
    loadData(page, query);
  });

  $(document).on("click", ".btn-delete", function (e) {
    e.preventDefault();
    const id = $(this).data("id");
    const row = $(this).closest("tr");

    if (confirm("Are you sure you want to delete this student?")) {
      $.ajax({
        url: "hps.php",
        type: "POST",
        dataType: "json",
        data: { id: id },
        success: function (response) {
          if (response.status === "success") {
            showFeedback("success", response.message);
            row.remove();
          } else {
            showFeedback("error", response.message);
          }
        },
        error: function () {
          showFeedback("error", "Error occurred while deleting.");
        },
      });
    }
  });
});
