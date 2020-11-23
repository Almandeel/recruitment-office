<table class="table table-bordered table-notes">
    <thead>
        @isset($noTitle)
        <tr>
            <th colspan="4" class="text-primary">
                <i class="fas fa-paperclip"></i>
                <span>الملاحظات</span>
            </th>
        </tr>
        @endisset
        <tr>
            <th style="width: 50px">#</th>
            <th>الاسم</th>
            <th>الملف</th>
            <th style="width: 50px">الخيارات</th>
        </tr>
    </thead>

    <tbody></tbody>
    <tfoot>
        <tr>
            <th>#</th>
            <th>الاسم</th>
            <th>الملف</th>
            <th>
                <button type="button" class="btn btn-primary btn-xs btn-add">
                    <i class="fa fa-plus"></i>
                    <span class="d-none d-md-inline">إضافة</span>
                </button>
            </th>
        </tr>
    </tfoot>
</table>