<a
    href="javascript:void(0)"
    onclick="updateEntry(this)"
    data-route="{{ url($crud->route.'/create_video_trending') }}"
    data-button-type="patch"
    class="btn btn-success" data-style="zoom-in"><span
        class="ladda-label"><i class="las la-plus-circle"></i> Make Video Trending </span></a>

<script>
    function updateEntry(button) {
        var route = $(button).attr("data-route");

        swal({
            title: "Make Video Trending By API Tiktok Now !!!",
            content: {
                element: "input",
                attributes: {
                    placeholder: "Type your duration video",
                    type: "number",
                },
            },
            icon: "warning",
            buttons: ["Cancel", "Yes"],
        }).then((value) => {
            if (value) {
                $.ajax({
                    url: route,
                    type: "post",
                    data: {'duration': value},
                    success: function (result) {
                        result = JSON.parse(result);
                        if (!result.error) {
                            swal({
                                title: result.message ?? "Success",
                                icon: "success",
                                timer: 4000,
                                buttons: {
                                    cancel: false,
                                    confirm: true,
                                },
                            });
                        } else {
                            swal({
                                title: result.message ?? "Failure",
                                icon: "error",
                                timer: 4000,
                                buttons: {
                                    cancel: false,
                                    confirm: true,
                                },
                            });
                        }

                        // Hide the modal, if any
                        $(".modal").modal("hide");
                    },
                    error: function (result) {
                        // Show an alert with the result
                        swal({
                            title: result.responseText ?? result.message ?? "Failure",
                            icon: "error",
                            timer: 4000,
                            buttons: {
                                cancel: false,
                                confirm: true,
                            },
                        });
                    },
                });
            } else {
                swal({
                    title: "Please input your duration video.",
                    icon: "error",
                    timer: 4000,
                    buttons: false,
                });
            }
        });
    }

    // make it so that the function above is run after each DataTable draw event
    // crud.addFunctionToDataTablesDrawEventQueue('updateEntry');
</script>
