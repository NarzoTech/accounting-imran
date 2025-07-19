<li class="menu-item {{ isRoute(['admin.invoice.*', 'admin.customer.*', 'admin.container.*'], 'active open') }}">


    <a href="javascript:void(0);" class="menu-link menu-toggle">
        <i class='menu-icon tf-icons bx bx-news'></i>
        <div class="text-truncate" data-i18n="{{ __('Sales & Payment') }}">{{ __('Sales & Payment') }}</div>
    </a>

    <ul class="menu-sub">
        <li class="menu-item {{ isRoute(['admin.invoice.*'], 'active') }}">
            <a class="menu-link" href="{{ route('admin.invoice.index') }}">
                {{ __('Invoice') }}
            </a>
        </li>
        <li class="menu-item {{ isRoute(['admin.customer.*'], 'active') }}">
            <a class="menu-link" href="{{ route('admin.customer.index') }}">
                {{ __('Customer') }}
            </a>
        </li>
        <li class="menu-item {{ isRoute(['admin.container.*'], 'active') }}">
            <a class="menu-link" href="{{ route('admin.container.index') }}">
                {{ __('Container') }}
            </a>
        </li>
    </ul>
</li>


<li class="menu-item {{ isRoute(['admin.product.*', 'admin.category.*'], 'active open') }}">


    <a href="javascript:void(0);" class="menu-link menu-toggle">
        <i class='menu-icon tf-icons bx bx-news'></i>
        <div class="text-truncate" data-i18n="{{ __('Product & Service') }}">{{ __('Product & Service') }}</div>
    </a>

    <ul class="menu-sub">
        <li class="menu-item {{ isRoute(['admin.product.*'], 'active') }}">
            <a class="menu-link" href="{{ route('admin.product.index') }}">
                {{ __('Product') }}
            </a>
        </li>
        <li class="menu-item {{ isRoute(['admin.category.*'], 'active') }}">
            <a class="menu-link" href="{{ route('admin.category.index') }}">
                {{ __('Category') }}
            </a>
        </li>
    </ul>
</li>

<li
    class="menu-item {{ isRoute(['admin.income.*', 'admin.transfer.*', 'admin.expense.*', 'admin.investor.*', 'admin.investment.*', 'admin.repayment.*', 'admin.account.*'], 'active open') }}">


    <a href="javascript:void(0);" class="menu-link menu-toggle">
        <i class='menu-icon tf-icons bx bx-news'></i>
        <div class="text-truncate" data-i18n="{{ __('Accounting') }}">{{ __('Accounting') }}</div>
    </a>

    <ul class="menu-sub">
        <li class="menu-item {{ isRoute(['admin.account.*'], 'active') }}">
            <a class="menu-link" href="{{ route('admin.account.index') }}">
                {{ __('Accounts') }}
            </a>
        </li>
        <li class="menu-item {{ isRoute(['admin.income.*'], 'active') }}">
            <a class="menu-link" href="{{ route('admin.income.index') }}">
                {{ __('Income') }}
            </a>
        </li>

        <li class="menu-item {{ isRoute(['admin.expense.*'], 'active') }}">
            <a class="menu-link" href="{{ route('admin.expense.index') }}">
                {{ __('Expenses') }}
            </a>
        </li>

        <li class="menu-item {{ isRoute(['admin.transfer.*'], 'active') }}">
            <a class="menu-link" href="{{ route('admin.transfer.index') }}">
                {{ __('Transfer') }}
            </a>
        </li>


        <li class="menu-item {{ isRoute(['admin.investment.*'], 'active') }}">
            <a class="menu-link" href="{{ route('admin.investment.index') }}">
                {{ __('Investment') }}
            </a>
        </li>

        <li class="menu-item {{ isRoute(['admin.investor.*'], 'active') }}">
            <a class="menu-link" href="{{ route('admin.investor.index') }}">
                {{ __('Investor') }}
            </a>
        </li>
    </ul>
</li>

{{-- reports Income
	Expenses
--}}

<li class="menu-item {{ isRoute(['admin.reports.income', 'admin.reports.expense'], 'active open') }}">
    <a href="javascript:void(0);" class="menu-link menu-toggle">
        <i class='menu-icon tf-icons bx bx-news'></i>
        <div class="text-truncate" data-i18n="{{ __('Reports') }}">{{ __('Reports') }}</div>
    </a>

    <ul class="menu-sub">
        <li class="menu-item {{ isRoute(['admin.reports.income'], 'active') }}">
            <a class="menu-link" href="{{ route('admin.reports.income') }}">
                {{ __('Income Report') }}
            </a>
        </li>
        <li class="menu-item {{ isRoute(['admin.reports.expense'], 'active') }}">
            <a class="menu-link" href="{{ route('admin.reports.expense') }}">
                {{ __('Expense Report') }}
            </a>
        </li>
    </ul>
</li>
