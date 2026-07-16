// Employees table
$(document).ready(function () {
    $("#employees").DataTable({
        destroy: true,
        dom: '<"flex flex-col md:flex-row justify-between items-center gap-4 mb-4 p-4"<"flex items-center gap-3"lB>f>rt<"flex flex-col md:flex-row justify-between items-center gap-4 mt-4 p-4 border-t border-gray-100"<"flex flex-col sm:flex-row items-center gap-4 text-sm text-gray-600"i><"flex items-center"p>>',
        buttons: [
            {
                extend: "excel",
                className:
                    "bg-green-50 hover:bg-green-100 text-green-700 px-3 py-1.5 text-sm font-medium rounded-lg border border-green-200 mr-2 transition-colors",
            },
            {
                extend: "pdf",
                className:
                    "bg-red-50 hover:bg-red-100 text-red-700 px-3 py-1.5 text-sm font-medium rounded-lg border border-red-200 mr-2 transition-colors",
            },
        ],
        pageLength: 10,
        language: {
            search: "",
            searchPlaceholder: "Search here...",
            lengthMenu: "_MENU_",
            info: "Showing _START_ to _END_ of _TOTAL_ entries",
        },
    });
});

// Departments table
$(document).ready(function () {
    $("#departments").DataTable({
        destroy: true,
        dom: '<"flex flex-col md:flex-row justify-between items-center gap-4 mb-4 p-4"<"flex items-center gap-3"lB>f>rt<"flex flex-col md:flex-row justify-between items-center gap-4 mt-4 p-4 border-t border-gray-100"<"flex flex-col sm:flex-row items-center gap-4 text-sm text-gray-600"i><"flex items-center"p>>',
        buttons: [
            {
                extend: "excel",
                className:
                    "bg-green-50 hover:bg-green-100 text-green-700 px-3 py-1.5 text-sm font-medium rounded-lg border border-green-200 mr-2 transition-colors",
            },
            {
                extend: "pdf",
                className:
                    "bg-red-50 hover:bg-red-100 text-red-700 px-3 py-1.5 text-sm font-medium rounded-lg border border-red-200 mr-2 transition-colors",
            },
        ],
        pageLength: 10,
        language: {
            search: "",
            searchPlaceholder: "Search here...",
            lengthMenu: "_MENU_",
            info: "Showing _START_ to _END_ of _TOTAL_ entries",
        },
    });
});

// Attendances table
$(document).ready(function () {
    $("#attendance").DataTable({
        destroy: true,
        dom: '<"flex flex-col md:flex-row justify-between items-center gap-4 mb-4 p-4"<"flex items-center gap-3"lB>f>rt<"flex flex-col md:flex-row justify-between items-center gap-4 mt-4 p-4 border-t border-gray-100"<"flex flex-col sm:flex-row items-center gap-4 text-sm text-gray-600"i><"flex items-center"p>>',
        buttons: [
            {
                extend: "excel",
                className:
                    "bg-green-50 hover:bg-green-100 text-green-700 px-3 py-1.5 text-sm font-medium rounded-lg border border-green-200 mr-2 transition-colors",
            },
            {
                extend: "pdf",
                className:
                    "bg-red-50 hover:bg-red-100 text-red-700 px-3 py-1.5 text-sm font-medium rounded-lg border border-red-200 mr-2 transition-colors",
            },
        ],
        pageLength: 10,
        language: {
            search: "",
            searchPlaceholder: "Search here...",
            lengthMenu: "_MENU_",
            info: "Showing _START_ to _END_ of _TOTAL_ entries",
        },
    });
});

// Leave types table
$(document).ready(function () {
    $("#leave_types").DataTable({
        destroy: true,
        dom: '<"flex flex-col md:flex-row justify-between items-center gap-4 mb-4 p-4"<"flex items-center gap-3"lB>f>rt<"flex flex-col md:flex-row justify-between items-center gap-4 mt-4 p-4 border-t border-gray-100"<"flex flex-col sm:flex-row items-center gap-4 text-sm text-gray-600"i><"flex items-center"p>>',
        buttons: [
            {
                extend: "excel",
                className:
                    "bg-green-50 hover:bg-green-100 text-green-700 px-3 py-1.5 text-sm font-medium rounded-lg border border-green-200 mr-2 transition-colors",
            },
            {
                extend: "pdf",
                className:
                    "bg-red-50 hover:bg-red-100 text-red-700 px-3 py-1.5 text-sm font-medium rounded-lg border border-red-200 mr-2 transition-colors",
            },
        ],
        pageLength: 10,
        language: {
            search: "",
            searchPlaceholder: "Search here...",
            lengthMenu: "_MENU_",
            info: "Showing _START_ to _END_ of _TOTAL_ entries",
        },
    });
});

// Leaves table
$(document).ready(function () {
    $("#leaves").DataTable({
        destroy: true,
        dom: '<"flex flex-col md:flex-row justify-between items-center gap-4 mb-4 p-4"<"flex items-center gap-3"lB>f>rt<"flex flex-col md:flex-row justify-between items-center gap-4 mt-4 p-4 border-t border-gray-100"<"flex flex-col sm:flex-row items-center gap-4 text-sm text-gray-600"i><"flex items-center"p>>',
        buttons: [
            {
                extend: "excel",
                className:
                    "bg-green-50 hover:bg-green-100 text-green-700 px-3 py-1.5 text-sm font-medium rounded-lg border border-green-200 mr-2 transition-colors",
            },
            {
                extend: "pdf",
                className:
                    "bg-red-50 hover:bg-red-100 text-red-700 px-3 py-1.5 text-sm font-medium rounded-lg border border-red-200 mr-2 transition-colors",
            },
        ],
        pageLength: 10,
        language: {
            search: "",
            searchPlaceholder: "Search here...",
            lengthMenu: "_MENU_",
            info: "Showing _START_ to _END_ of _TOTAL_ entries",
        },
    });
});

// Holidays table
$(document).ready(function () {
    $("#holidays").DataTable({
        destroy: true,
        dom: '<"flex flex-col md:flex-row justify-between items-center gap-4 mb-4 p-4"<"flex items-center gap-3"lB>f>rt<"flex flex-col md:flex-row justify-between items-center gap-4 mt-4 p-4 border-t border-gray-100"<"flex flex-col sm:flex-row items-center gap-4 text-sm text-gray-600"i><"flex items-center"p>>',
        buttons: [
            {
                extend: "excel",
                className:
                    "bg-green-50 hover:bg-green-100 text-green-700 px-3 py-1.5 text-sm font-medium rounded-lg border border-green-200 mr-2 transition-colors",
            },
            {
                extend: "pdf",
                className:
                    "bg-red-50 hover:bg-red-100 text-red-700 px-3 py-1.5 text-sm font-medium rounded-lg border border-red-200 mr-2 transition-colors",
            },
        ],
        pageLength: 10,
        language: {
            search: "",
            searchPlaceholder: "Search here...",
            lengthMenu: "_MENU_",
            info: "Showing _START_ to _END_ of _TOTAL_ entries",
        },
    });
});

// Task table - 1. My task 2. tast to testing 3. delegated task
$(document).ready(function () {
    // Tab switching logic
    $("#taskTab button").on("click", function () {
        // Clear active styles
        $("#taskTab button")
            .removeClass(
                "border-indigo-600 text-indigo-650 dark:border-purple-500 dark:text-purple-400 font-bold",
            )
            .addClass(
                "border-transparent text-gray-500 hover:text-gray-600 hover:border-gray-300 dark:text-zinc-400 dark:hover:text-zinc-300",
            );

        // Set active style on current
        $(this)
            .removeClass(
                "border-transparent text-gray-500 hover:text-gray-600 hover:border-gray-300 dark:text-zinc-400 dark:hover:text-zinc-300",
            )
            .addClass(
                "border-indigo-600 text-indigo-650 dark:border-purple-500 dark:text-purple-400 font-bold",
            );

        // Toggle panels
        $(".tab-content").addClass("hidden");
        $($(this).data("tab-target")).removeClass("hidden");

        // Save active tab ID to localStorage
        localStorage.setItem("activeTaskTab", $(this).attr("id"));

        // Adjust column calculation for active Datatables
        setTimeout(function () {
            $.fn.dataTable
                .tables({
                    visible: true,
                    api: true,
                })
                .columns.adjust();
        }, 50);
    });

    // Initialize Datatables [Tab switching logic included]
    $("#my_tasks").DataTable({
        destroy: true,
        dom: '<"flex flex-col md:flex-row justify-between items-center gap-4 mb-4 p-4"<"flex items-center gap-3"lB>f>rt<"flex flex-col md:flex-row justify-between items-center gap-4 mt-4 p-4 border-t border-gray-100 dark:border-zinc-800"<"flex flex-col sm:flex-row items-center gap-4 text-sm text-gray-600 dark:text-zinc-455"i><"flex items-center"p>>',
        buttons: [
            {
                extend: "excel",
                className:
                    "bg-green-50 hover:bg-green-100 text-green-700 px-3 py-1.5 text-sm font-medium rounded-lg border border-green-200 mr-2 transition-colors",
            },
            {
                extend: "pdf",
                className:
                    "bg-red-50 hover:bg-red-100 text-red-700 px-3 py-1.5 text-sm font-medium rounded-lg border border-red-200 mr-2 transition-colors",
            },
        ],
        pageLength: 10,
        language: {
            search: "",
            searchPlaceholder: "Search here...",
            lengthMenu: "_MENU_",
            info: "Showing _START_ to _END_ of _TOTAL_ entries",
        },
    });

    $("#assigned_tasks").DataTable({
        destroy: true,
        dom: '<"flex flex-col md:flex-row justify-between items-center gap-4 mb-4 p-4"<"flex items-center gap-3"lB>f>rt<"flex flex-col md:flex-row justify-between items-center gap-4 mt-4 p-4 border-t border-gray-100 dark:border-zinc-800"<"flex flex-col sm:flex-row items-center gap-4 text-sm text-gray-600 dark:text-zinc-455"i><"flex items-center"p>>',
        buttons: [
            {
                extend: "excel",
                className:
                    "bg-green-50 hover:bg-green-100 text-green-700 px-3 py-1.5 text-sm font-medium rounded-lg border border-green-200 mr-2 transition-colors",
            },
            {
                extend: "pdf",
                className:
                    "bg-red-50 hover:bg-red-100 text-red-700 px-3 py-1.5 text-sm font-medium rounded-lg border border-red-200 mr-2 transition-colors",
            },
        ],
        pageLength: 10,
        language: {
            search: "",
            searchPlaceholder: "Search here...",
            lengthMenu: "_MENU_",
            info: "Showing _START_ to _END_ of _TOTAL_ entries",
        },
    });

    if ($("#tasks_to_test").length) {
        $("#tasks_to_test").DataTable({
            destroy: true,
            dom: '<"flex flex-col md:flex-row justify-between items-center gap-4 mb-4 p-4"<"flex items-center gap-3"lB>f>rt<"flex flex-col md:flex-row justify-between items-center gap-4 mt-4 p-4 border-t border-gray-100 dark:border-zinc-800"<"flex flex-col sm:flex-row items-center gap-4 text-sm text-gray-600 dark:text-zinc-455"i><"flex items-center"p>>',
            buttons: [
                {
                    extend: "excel",
                    className:
                        "bg-green-50 hover:bg-green-100 text-green-700 px-3 py-1.5 text-sm font-medium rounded-lg border border-green-200 mr-2 transition-colors",
                },
                {
                    extend: "pdf",
                    className:
                        "bg-red-50 hover:bg-red-100 text-red-700 px-3 py-1.5 text-sm font-medium rounded-lg border border-red-200 mr-2 transition-colors",
                },
            ],
            pageLength: 10,
            language: {
                search: "",
                searchPlaceholder: "Search here...",
                lengthMenu: "_MENU_",
                info: "Showing _START_ to _END_ of _TOTAL_ entries",
            },
        });
    }

    // Restore active tab from localStorage if exists
    var activeTab = localStorage.getItem("activeTaskTab");
    if (activeTab && $("#" + activeTab).length) {
        $("#" + activeTab).trigger("click");
    } else {
        $("#my-tasks-tab").trigger("click");
    }
});

// Defects table
$(document).ready(function () {
    $("#defects_table").DataTable({
        destroy: true,
        dom: '<"flex flex-col md:flex-row justify-between items-center gap-4 mb-4 p-4"<"flex items-center gap-3"lB>f>rt<"flex flex-col md:flex-row justify-between items-center gap-4 mt-4 p-4 border-t border-gray-100"<"flex flex-col sm:flex-row items-center gap-4 text-sm text-gray-600"i><"flex items-center"p>>',
        buttons: [
            {
                extend: "excel",
                className:
                    "bg-green-50 hover:bg-green-100 text-green-700 px-3 py-1.5 text-sm font-medium rounded-lg border border-green-200 mr-2 transition-colors",
            },
            {
                extend: "pdf",
                className:
                    "bg-red-50 hover:bg-red-100 text-red-700 px-3 py-1.5 text-sm font-medium rounded-lg border border-red-200 mr-2 transition-colors",
            },
        ],
        pageLength: 10,
        language: {
            search: "",
            searchPlaceholder: "Search defects...",
            lengthMenu: "_MENU_",
            info: "Showing _START_ to _END_ of _TOTAL_ defects",
        },
    });
});

// Break types table
$(document).ready(function () {
    $("#break-types-table").DataTable({
        destroy: true,
        dom: '<"flex flex-col md:flex-row justify-between items-center gap-4 mb-4 p-4"<"flex items-center gap-3"lB>f>rt<"flex flex-col md:flex-row justify-between items-center gap-4 mt-4 p-4 border-t border-gray-100"<"flex flex-col sm:flex-row items-center gap-4 text-sm text-gray-600"i><"flex items-center"p>>',
        buttons: [
            {
                extend: "excel",
                className:
                    "bg-green-50 hover:bg-green-100 text-green-700 px-3 py-1.5 text-sm font-medium rounded-lg border border-green-200 mr-2 transition-colors",
            },
            {
                extend: "pdf",
                className:
                    "bg-red-50 hover:bg-red-100 text-red-700 px-3 py-1.5 text-sm font-medium rounded-lg border border-red-200 mr-2 transition-colors",
            },
        ],
        pageLength: 10,
        language: {
            search: "",
            searchPlaceholder: "Search break types...",
            lengthMenu: "_MENU_",
            info: "Showing _START_ to _END_ of _TOTAL_ entries",
        },
    });
});

// Announcements table
$(document).ready(function () {
    $("#announcements").DataTable({
        destroy: true,
        dom: '<"flex flex-col md:flex-row justify-between items-center gap-4 mb-4 p-4"<"flex items-center gap-3"lB>f>rt<"flex flex-col md:flex-row justify-between items-center gap-4 mt-4 p-4 border-t border-gray-100"<"flex flex-col sm:flex-row items-center gap-4 text-sm text-gray-600"i><"flex items-center"p>>',
        buttons: [
            {
                extend: "excel",
                className:
                    "bg-green-50 hover:bg-green-100 text-green-700 px-3 py-1.5 text-sm font-medium rounded-lg border border-green-200 mr-2 transition-colors",
            },
            {
                extend: "pdf",
                className:
                    "bg-red-50 hover:bg-red-100 text-red-700 px-3 py-1.5 text-sm font-medium rounded-lg border border-red-200 mr-2 transition-colors",
            },
        ],
        pageLength: 10,
        language: {
            search: "",
            searchPlaceholder: "Search here...",
            lengthMenu: "_MENU_",
            info: "Showing _START_ to _END_ of _TOTAL_ entries",
        },
    });
});

// Roles & Permissions table
$(document).ready(function () {
    $("#role").DataTable({
        destroy: true,
        dom: '<"flex flex-col md:flex-row justify-between items-center gap-4 mb-4 p-4"<"flex items-center gap-3"lB>f>rt<"flex flex-col md:flex-row justify-between items-center gap-4 mt-4 p-4 border-t border-gray-100"<"flex flex-col sm:flex-row items-center gap-4 text-sm text-gray-600"i><"flex items-center"p>>',
        buttons: [
            {
                extend: "excel",
                className:
                    "bg-green-50 hover:bg-green-100 text-green-700 px-3 py-1.5 text-sm font-medium rounded-lg border border-green-200 mr-2 transition-colors",
            },
            {
                extend: "pdf",
                className:
                    "bg-red-50 hover:bg-red-100 text-red-700 px-3 py-1.5 text-sm font-medium rounded-lg border border-red-200 mr-2 transition-colors",
            },
        ],
        pageLength: 10,
        language: {
            search: "",
            searchPlaceholder: "Search here...",
            lengthMenu: "_MENU_",
            info: "Showing _START_ to _END_ of _TOTAL_ entries",
        },
    });
});

// Reports - Employee
$(document).ready(function () {
    $("#report-table").DataTable({
        destroy: true,
        dom: '<"flex flex-col md:flex-row justify-between items-center gap-4 mb-4 p-4"<"flex items-center gap-3"lB>f>rt<"flex flex-col md:flex-row justify-between items-center gap-4 mt-4 p-4 border-t border-gray-100"<"flex flex-col sm:flex-row items-center gap-4 text-sm text-gray-600"i><"flex items-center"p>>',
        buttons: [
            {
                extend: "excel",
                className:
                    "bg-green-50 hover:bg-green-100 text-green-700 px-3 py-1.5 text-sm font-medium rounded-lg border border-green-200 mr-2 transition-colors",
            },
            {
                extend: "pdf",
                className:
                    "bg-red-50 hover:bg-red-100 text-red-700 px-3 py-1.5 text-sm font-medium rounded-lg border border-red-200 mr-2 transition-colors",
            },
        ],
        pageLength: 10,
        language: {
            search: "",
            searchPlaceholder: "Search here...",
            lengthMenu: "_MENU_",
            info: "Showing _START_ to _END_ of _TOTAL_ entries",
        },
    });
});
