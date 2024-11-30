$.ajaxSetup({
    headers: {
        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
    },
});

$("#image").change(function () {
    const form = new FormData();
    form.append("file", $(this)[0].files[0]); // Lấy tệp tin từ input file

    $.ajax({
        processData: false,
        contentType: false,
        type: "POST",
        dataType: "JSON",
        data: form,
        url: "/api/upload/services",
        success: function (data) {
            if (data.error === false) {
                // Gán URL trả về từ API vào input
                $("#file").val(data.url);

                // Cập nhật AngularJS scope
                const scope = angular.element($("#file")).scope(); // Lấy scope AngularJS
                scope.$apply(function () {
                    scope.product.file = data.url; // Gán URL vào ng-model
                });

                console.log("Uploaded file URL:", data.url);
            } else {
                alert(data.error);
            }
        },
        error: function () {
            alert("Có lỗi xảy ra khi tải tệp.");
        },
    });
});
