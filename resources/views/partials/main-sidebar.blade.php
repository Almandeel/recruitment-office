<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-light-primary elevation-4">

    <!-- Brand Logo -->
    <a href="#" class="brand-link">
        {{--  <img src="{{ asset("dashboard/dist/img/AdminLTELogo.png") }}" alt="Dook" class="brand-image img-circle elevation-3" style="opacity: .8">  --}}
        <span class="brand-text font-weight-light text-capitalize">{{ auth()->guard('office')->check() ? auth()->user()->office->name : config('app.name')  }}</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                @if ((request()->segment(1) == 'hr'))
                    @permission('employees-read')
                        <li class="nav-item"><h3>نظام الموارد البشرية</h3></li>
                    @endpermission

                    @permission('departments-read')
                        <li class="nav-item">
                            <a href="{{ route('departments.index') }}" class="nav-link {{ (request()->segment(2) == 'departments') ? 'active' : '' }}">
                                <i class="fa fa-circle-o nav-icon"></i>
                                <p>الاقسام</p>
                            </a>
                        </li>
                    @endpermission

                    @permission('positions-read')
                        <li class="nav-item">
                            <a href="{{ route('positions.index') }}" class="nav-link {{ (request()->segment(2) == 'positions') ? 'active' : '' }}">
                                <i class="fa fa-circle-o nav-icon"></i>
                                <p>الوظائف</p>
                            </a>
                        </li>
                    @endpermission

                    @permission('employees-read')
                        <li class="nav-item">
                            <a href="{{ route('employees.index') }}" class="nav-link {{ (request()->segment(2) == 'employees') ? 'active' : '' }}">
                                <i class="fa fa-circle-o nav-icon"></i>
                                <p>الموظفين</p>
                            </a>
                        </li>
                    @endpermission

                    @permission('vacations-read')
                        <li class="nav-item">
                            <a href="{{ route('vacations.index') }}" class="nav-link">
                                <i class="fa fa-circle-o nav-icon"></i>
                                <p>الاجازات</p>
                            </a>
                        </li>
                    @endpermission

                    @permission('attendance-read')
                        <li class="nav-item">
                            <a href="{{ route('attendance.index') }}" class="nav-link">
                                <i class="fa fa-circle-o nav-icon"></i>
                                <p>الحضور</p>
                            </a>
                        </li>
                    @endpermission
                    {{--  @permission('transactions-read')
                    <li class="nav-item">
                        <a href="{{ route('transactions.index') }}" class="nav-link {{ (request()->segment(2) == 'transactions') ? 'active' : '' }}">
                            <i class="fa fa-circle-o nav-icon"></i>
                            <p>المعاملات المالية</p>
                        </a>
                    </li>
                    @endpermission  --}}
                    {{--  @permission('salaries-read')
                    <li class="nav-item">
                        <a href="{{ route('salaries.index') }}" class="nav-link {{ (request()->segment(2) == 'salaries') ? 'active' : '' }}">
                            <i class="fa fa-circle-o nav-icon"></i>
                            <p>المرتبات</p>
                        </a>
                    </li>
                    @endpermission  --}}
                    {{--  @permission('custodies-read')
                    <li class="nav-item">
                        <a href="{{ route('custodies.index') }}" class="nav-link {{ (request()->segment(2) == 'custodies') ? 'active' : '' }}">
                            <i class="fa fa-circle-o nav-icon"></i>
                            <p>العهد</p>
                        </a>
                    </li>
                    @endpermission  --}}
                @elseif ((request()->segment(1) == 'accounting'))
                    <li class="nav-item"><h3>{!! config('accounting.name') !!}</h3></li>
                    <li class="nav-item">
                        <a href="{{ route('accounting.dashboard') }}" class="nav-link {{ (request()->segment(2) == '') ? 'active' : '' }}">
                            <i class="fa fa-dashboard nav-icon"></i>
                            <p>لوحة التحكم</p>
                        </a>
                    </li>
                    {{--  <li class="nav-item">
                        <a href="{{ route('accounting.tree') }}" class="nav-link {{ (request()->segment(2) == 'tree') ? 'active' : '' }}">
                            <i class="fa fa-sort-desc nav-icon"></i>
                            <p>شجرة الحسابات</p>
                        </a>
                    </li>  --}}
                    @permission('entries-read')
                    <li class="nav-item">
                        <a href="{{ route('entries.index') }}" class="nav-link {{ (request()->segment(2) == 'entries') ? 'active' : '' }}">
                            <i class="fa fa-circle-o nav-icon"></i>
                            <p>دفتر اليومية</p>
                        </a>
                    </li>
                    @endpermission
                    @permission('accounts-read')
                    <li class="nav-item">
                        <a href="{{ route('accounts.index') }}" class="nav-link {{ (request()->segment(2) == 'accounts') ? 'active' : '' }}">
                            <i class="fa fa-circle-o nav-icon"></i>
                            <p>الحسابات</p>
                        </a>
                    </li>
                    @endpermission
                    @permission('years-read')
                    <li class="nav-item">
                        <a href="{{ route('years.index') }}" class="nav-link {{ (request()->segment(2) == 'years') ? 'active' : '' }}">
                            <i class="fa fa-circle-o nav-icon"></i>
                            <p>السنوات المالية</p>
                        </a>
                    </li>
                    @endpermission
                    @permission('centers-read')
                    <li class="nav-item">
                        <a href="{{ route('centers.index') }}" class="nav-link {{ (request()->segment(2) == 'centers') ? 'active' : '' }}">
                            <i class="fa fa-circle-o nav-icon"></i>
                            <p>المراكز</p>
                        </a>
                    </li>
                    @endpermission
                    @permission('safes-read')
                    <li class="nav-item">
                        <a href="{{ route('safes.index') }}" class="nav-link {{ (request()->segment(2) == 'safes') ? 'active' : '' }}">
                            <i class="fa fa-circle-o nav-icon"></i>
                            <p>الخزن</p>
                        </a>
                    </li>
                    @endpermission
                    @permission('accounts-print')
                    <li class="nav-item">
                        <a href="{{ route('accounting.reports') }}" class="nav-link {{ (request()->segment(2) == 'reports') ? 'active' : '' }}">
                            <i class="fa fa-sort-desc nav-icon"></i>
                            <p>التقارير</p>
                        </a>
                    </li>
                    @endpermission
                @elseif ((request()->segment(1) == 'users'))
                @permission('users-read')
                    <li class="nav-item"><h3>نظام المستخدمين</h3></li>
                @endpermission

                @permission('users-create')
                    <li class="nav-item"><a href="{{ route('users.create') }}" class="nav-link {{ (request()->segment(2) == 'users' && request()->segment(3) == 'create') ? 'active' : '' }}">
                        <i class="fa fa-plus nav-icon"></i>
                        <p>انشاء مستخدم</p>
                    </a></li>
                @endpermission

                @permission('roles-create')
                    <li class="nav-item"><a href="{{ route('roles.create') }}" class="nav-link {{ (request()->segment(2) == 'roles' && request()->segment(3) == 'create') ? 'active' : '' }}">
                        <i class="fa fa-plus nav-icon"></i>
                        <p>انشاء دور</p>
                    </a></li>
                @endpermission

                @permission('roles-read')
                    <li class="nav-item"><a href="{{ route('roles.index') }}" class="nav-link {{ (request()->segment(2) == 'roles' && request()->segment(3) == '') ? 'active' : '' }}">
                        <i class="fas fa-list nav-icon"></i>
                        <p>الادوار</p>
                    </a></li>
                @endpermission

                @permission('users-read')
                    <li class="nav-item"><a href="{{ route('users.index') }}" class="nav-link {{ (request()->segment(2) == 'users' && request()->segment(3) == '') ? 'active' : '' }}">
                        <i class="fas fa-users nav-icon"></i>
                        <p>المستخدمين</p>
                    </a></li>
                @endpermission
                @elseif ((request()->segment(1) == 'mail'))
                @permission('mail-read')
                    <li class="nav-item"><h3>نظام المراسلات</h3></li>

                    <li class="nav-item"><a href="{{ route('mail.create') }}" class="nav-link {{ (request()->segment(2) == 'create') ? 'active' : '' }}">
                            <i class="fa fa-plus nav-icon"></i>
                            <p>انشاء رسالة</p>
                    </a></li>

                    <li><a href="{{ route('mail.index', ['box' => Modules\Mail\Models\Letter::BOX_INBOX]) }}" class="nav-link {{ (!request('box') && request()->segment(2) !== 'create') || request('box') == Modules\Mail\Models\Letter::BOX_INBOX ? 'active' : '' }}">
                            <i class="fas fa-inbox nav-icon"></i>
                            <p>
                                <span>صندوق الوارد</span>
                                {{--  <span class="right badge badge-primary">45</span>  --}}
                            </p>
                    </a></li>

                    <li><a href="{{ route('mail.index', ['box' => Modules\Mail\Models\Letter::BOX_OUTBOX]) }}" class="nav-link {{ request('box') == Modules\Mail\Models\Letter::BOX_OUTBOX ? 'active' : '' }}">
                            <i class="fas fa-box nav-icon"></i>
                            <p>صندوق المرسل</p>
                    </a></li>

                    <li><a href="{{ route('mail.index', ['box' => Modules\Mail\Models\Letter::BOX_DRAFTS]) }}" class="nav-link {{ request('box') == Modules\Mail\Models\Letter::BOX_DRAFTS ? 'active' : '' }}">
                            <i class="fas fa-save nav-icon"></i>
                            <p>صندوق المحفوظات</p>
                    </a></li>
                @endpermission
                @elseif ((request()->segment(1) == 'warehouses'))
                    @permission('warehouses-read')
                        <li class="nav-item">
                            <a href="{{ url('/warehouses') }}" class="nav-link">
                                <i class="fa fa-circle-o nav-icon"></i>
                                <p> الرئيسية</p>
                            </a>
                        </li>
                    @endpermission

                    @permission('warehouses-read')
                        <li class="nav-item">
                            <a href="{{ route('warehouses.index') }}" class="nav-link">
                                <i class="fa fa-circle-o nav-icon"></i>
                                <p>الايواء</p>
                            </a>
                        </li>
                    @endpermission
                @elseif ((request()->segment(1) == 'tutorial'))
                    @permission('tutorials-read')
                        <li class="nav-item">
                            <a href="{{ url('/tutorial') }}" class="nav-link">
                                <i class="fa fa-circle-o nav-icon"></i>
                                <p>الرئيسية</p>
                            </a>
                        </li>
                    @endpermission

                    @permission('tutorials-read')
                        <li class="nav-item">
                            <a href="{{ route('tutorials.index') }}" class="nav-link" {{ request()->segment(2) == 'tutorials' ? 'active' : '' }}>
                                <i class="fa fa-circle-o nav-icon"></i>
                                <p>مركز المعرفة</p>
                            </a>
                        </li>
                    @endpermission

                    @permission('categories-read')
                        <li class="nav-item">
                            <a href="{{ route('categories.index') }}" class="nav-link"  {{ request()->segment(2) == 'categories' ? 'active' : '' }}>
                                <i class="fa fa-circle-o nav-icon"></i>
                                <p>الاقسام</p>
                            </a>
                        </li>
                    @endpermission

                    @permission('tutorials-create')
                        <li class="nav-item">
                            <a href="{{ route('tutorials.create') }}" class="nav-link" {{ request()->segment(2) == 'tutorials' && request()->segment(3) == 'create' ? 'active' : '' }}>
                                <i class="fa fa-circle-o nav-icon"></i>
                                <p>اضافة مقال</p>
                            </a>
                        </li>
                    @endpermission
                    {{-- <li class="nav-item">
                        <a href="{{ route('tutorials.create') }}" class="nav-link">
                            <i class="fa fa-circle-o nav-icon"></i>
                            <p>اضافة قسم</p>
                        </a>
                    </li> --}}
                @elseif ((request()->segment(1) == 'services'))

                @permission('customers-create')
                    <li class="nav-item">
                        <a href="{{ route('services.dashboard') }}" class="nav-link {{ (request()->segment(1) == 'services') ? 'active' : '' }}">
                            <i class="fab fa-dashboard nav-icon"></i>
                            <p>لوحة التحكم</p>
                        </a>
                    </li>
                @endpermission

                @permission('contracts-create')
                    <li class="nav-item">
                        <a href="{{ route('contracts.create') }}"  class="nav-link {{ (request()->segment(2) == 'contracts' && request()->segment(3) == 'create') ? 'active' : '' }}" >
                            <i class="fa fa-plus nav-icon"></i>
                            <p> اضافة عقد </p>
                        </a>
                    </li>
                @endpermission

                @permission('cv-create')
                    <li class="nav-item">
                        <a href="{{ route('servicescvs.create') }}"  class="nav-link {{ (request()->segment(2) == 'servicescvs' && request()->segment(3) == 'create') ? 'active' : '' }}">
                            <i class="fa fa-plus nav-icon"></i>
                            <p> اضافة CV </p>
                        </a>
                    </li>
                @endpermission


                @permission('customers-create')
                    <li class="nav-item">
                        <a href="#" data-toggle="modal" data-target="#customerModal"  class="nav-link {{ (request()->segment(2) == 'customers' && request()->segment(3) == 'create') ? 'active' : '' }}">
                            <i class="fa fa-plus nav-icon"></i>
                            <p> اضافة عميل </p>
                        </a>
                    </li>
                @endpermission

                @permission('complaints-create')
                    <li class="nav-item">
                        <a href="{{ route('complaints.create') }}"  class="nav-link  {{ (request()->segment(2) == 'complaints' && request()->segment(3) == 'create') ? 'active' : '' }}">
                            <i class="fa fa-plus nav-icon"></i>
                            <p> اضافة شكوى </p>
                        </a>
                    </li>
                @endpermission

                @permission('complaints-read')
                    <li class="nav-item">
                        <a href="{{ route('complaints.index') }}" class="nav-link {{ (request()->segment(2) == 'complaints') ? 'active' : '' }}">
                            <i class="fa fa-balance-scale nav-icon"></i>
                            <p>
                                الشكاوي
                            </p>
                        </a>
                    </li>
                @endpermission

                @permission('contracts-read')
                    <li class="nav-item">
                        <a href="{{ route('contracts.index') }}" class="nav-link {{ (request()->segment(2) == 'contracts') ? 'active' : '' }}">
                            <i class="fa fa-file-word nav-icon"></i>
                            <p>العقود</p>
                        </a>
                    </li>
                @endpermission

                @permission('cv-read')
                    <li class="nav-item">
                        <a href="{{ route('servicescvs.index') }}" class="nav-link {{ (request()->segment(2) == 'servicescvs') ? 'active' : '' }}">
                            <i class="fa fa-file-word nav-icon"></i>
                            <p>السير الذاتية</p>
                        </a>
                    </li>
                @endpermission

                @permission('bails-read')
                    <li class="nav-item">
                        {{--  <a href="{{ route('kafalat.index') }}" class="nav-link {{ (request()->segment(2) == 'servicesmarketers') ? 'active' : '' }}">  --}}
                        <a href="{{ route('bails.index') }}" class="nav-link {{ (request()->segment(2) == 'bails') ? 'active' : '' }}">
                            <i class="fa fa-user nav-icon"></i>
                            <p>  نقل الكفالة</p>
                        </a>
                    </li>
                @endpermission


                @permission('delegations-read')
                    <li class="nav-item">
                        <a href="{{ route('tafweed.index') }}" class="nav-link {{ (request()->segment(2) == 'tafweeds') ? 'active' : '' }}">
                            <i class="fa fa-home nav-icon"></i>
                            <p>الوكالات</p>
                        </a>
                    </li>
                @endpermission

                @permission('customers-read')
                    <li class="nav-item">
                        <a href="{{ route('customers.index') }}" class="nav-link {{ (request()->segment(2) == 'customers') ? 'active' : '' }}">
                            <i class="fa fa-users nav-icon"></i>
                            <p>العملاء</p>
                        </a>
                    </li>
                @endpermission

                @permission('marketers-read')
                    <li class="nav-item">
                        <a href="{{ route('servicesmarketers.index') }}" class="nav-link {{ (request()->segment(2) == 'servicesmarketers') ? 'active' : '' }}">
                            <i class="fa fa-users nav-icon"></i>
                            <p>المسوقين</p>
                        </a>
                    </li>
                @endpermission
                @permission('flights-read')
                    <li class="nav-item">
                        <a href="{{ route('services.flights.index') }}" class="nav-link">
                            <i class="fa fa-plane-arrival nav-icon"></i>
                            <p>الرحلات</p>
                        </a>
                    </li>
                @endpermission
                @elseif ((request()->segment(1) == 'office'))
                    @permission('cv-read')
                    <li class="nav-item"> <a href="{{ route('cvs.index') }}" class="nav-link"> <i class="fa fa-circle-o nav-icon"></i> <p> Cvs </p> </a> </li>
                    @endpermission
                    @permission('return-read')
                    <li class="nav-item {{ (request()->segment(2) == 'returns') ? 'active' : ''}}"><a href="{{ route('office.returns.index') }}" class="nav-link"> <i class="fa fa-circle-o nav-icon"></i> <p> Returned Cvs </p> </a></li>
                    @endpermission
                    @permission('pulls-read')
                    <li class="nav-item {{ (request()->segment(2) == 'pulls') ? 'active' : ''}}"><a href="{{ route('office.pulls.index') }}" class="nav-link"> <i class="fa fa-circle-o nav-icon"></i> <p>Pulled Cvs </p> </a></li>
                    @endpermission
                    @permission('flights-read')
                    <li class="nav-item {{ (request()->segment(2) == 'flights') ? 'active' : ''}}"><a href="{{ route('office.flights.index') }}" class="nav-link"> <i class="fa fa-circle-o nav-icon"></i> <p>Flights </p> </a></li>
                    @endpermission
                    @if (auth()->user()->isAbleTo('advances-read|bills-read'))
                    <li class="nav-item has-treeview @if (request()->segment(3) == 'bills'  || request()->segment(2) == 'advances') menu-open @endif">
                        <a href="#" class="nav-link"> <i class="nav-icon fa fa-dashboard"></i> <p> Finance Section <i class="fa fa-angle-right"></i> </p> </a>
                        <ul class="nav nav-treeview">
                            @permission('advances-read')
                            <li class="nav-item"> <a href="{{ route('advances.index') }}" class="nav-link"> <i class="fa fa-circle-o nav-icon"></i> <p> Advances </p> </a> </li>
                            @endpermission
                            @permission('bills-read')
                            <li class="nav-item"> <a href="{{ route('cvs.bills.index') }}" class="nav-link"> <i class="fa fa-circle-o nav-icon"></i> <p> Bills </p> </a> </li>
                            @endpermission
                        </ul>
                    </li>
                    @endif
                    @if (auth()->user()->isAbleTo('roles-read|users-read'))
                    <li class="nav-item has-treeview @if (request()->segment(3) == 'users'  || request()->segment(2) == 'roles') menu-open @endif">
                        <a href="#" class="nav-link"> <i class="nav-icon fa fa-dashboard"></i> <p> Authentication Section <i class="fa fa-angle-right"></i> </p> </a>
                        <ul class="nav nav-treeview">
                            @permission('roles-read')
                            <li class="nav-item"> <a href="{{ route('office.roles.index') }}" class="nav-link"> <i class="fa fa-circle-o nav-icon"></i> <p> Roles </p> </a> </li>
                            @endpermission
                            @permission('users-read')
                            <li class="nav-item"> <a href="{{ route('office.users.index') }}" class="nav-link"> <i class="fa fa-circle-o nav-icon"></i> <p> Users </p> </a> </li>
                            @endpermission
                        </ul>
                    </li>
                    @endif
                @elseif ((request()->segment(1) == 'report'))
                    <li class="nav-item"> <a href="{{ route('report.hr') }}" class="nav-link {{ (request()->segment(2) == 'hr') ? 'active' : '' }}"> <i class="fa fa-circle-o nav-icon"></i> <p> تقارير الموارد البشرية </p> </a> </li>
                    <li class="nav-item"> <a href="{{ route('report.services') }}" class="nav-link {{ (request()->segment(2) == 'services') ? 'active' : '' }}"> <i class="fa fa-circle-o nav-icon"></i> <p> تقارير خدمات العملاء  </p> </a> </li>
                @elseif ((request()->segment(1) == 'offices'))
                    @permission('offices-read')
                        <li class="nav-item"> <a href="{{ route('offices.index') }}" class="nav-link"> <i class="fa fa-circle-o nav-icon"></i> <p>المكاتب الخارجية</p> </a> </li>
                    @endpermission
                    @permission('professions-read')
                        <li class="nav-item"> <a href="{{ route('mainprofessions.index') }}" class="nav-link"> <i class="fa fa-circle-o nav-icon"></i> <p> المهن  </p> </a> </li>
                    @endpermission

                    @permission('countries-read')
                        <li class="nav-item"> <a href="{{ route('maincountries.index') }}" class="nav-link"> <i class="fa fa-circle-o nav-icon"></i> <p> الدول  </p> </a> </li>
                    @endpermission
                    @permission('advances-read')
                        <li class="nav-item"> <a href="{{ route('mainadvances.index') }}" class="nav-link"> <i class="fa fa-circle-o nav-icon"></i> <p> السلفيات  </p> </a> </li>
                    @endpermission
                    @permission('bills-read')
                        <li class="nav-item"> <a href="{{ route('offices.bills.index') }}" class="nav-link"> <i class="fa fa-circle-o nav-icon"></i> <p> الفواتير  </p> </a> </li>
                    @endpermission
                    @permission('returns-read')
                        <li class="nav-item"> <a href="{{ route('offices.returns.index') }}" class="nav-link"> <i class="fa fa-circle-o nav-icon"></i> <p> المرتجعات  </p> </a> </li>
                    @endpermission
                    @permission('pulls-read')
                        <li class="nav-item"> <a href="{{ route('offices.pulls.index') }}" class="nav-link"> <i class="fa fa-circle-o nav-icon"></i> <p> المسحوبات  </p> </a> </li>
                    @endpermission
                        {{-- <li class="nav-item"> <a href="{{ route('maincvs.index') }}" class="nav-link"> <i class="fa fa-circle-o nav-icon"></i> <p> السير الذاتية  </p> </a> </li> --}}
                @endif
                {{--  @if (!auth()->guard('office')->check())
                    <li class="nav-item"> <a href="{{ route('users.profile') }}" class="nav-link"> <i class="fa fa-circle-o nav-icon"></i> <p> الملف الشخصي </p> </a> </li>
                    @permission('tasks-read')
                        <li class="nav-item"> <a href="{{ route('tasks.index') }}" class="nav-link"> <i class="fa fa-circle-o nav-icon"></i> <p> المهام </p> </a> </li>
                    @endpermission
                    @permission('suggestions-read')
                        <li class="nav-item"> <a href="{{ route('suggestions.index') }}" class="nav-link"> <i class="fa fa-circle-o nav-icon"></i> <p> الاقتراحات </p> </a> </li>
                    @endpermission
                    @permission('logs-read')
                        <li class="nav-item"> <a href="{{ route('logs.index') }}" class="nav-link"> <i class="fa fa-circle-o nav-icon"></i> <p> السجلات </p> </a> </li>
                    @endpermission
                @endif  --}}
            </ul>
            {{-- <div class="text-center">
                <button type="button" class="btn btn-secondary logout">
                    <i class="fa fa-power-off"></i>
                    <span>تسجيل الخروج</span>
                </button>
            </div> --}}
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

