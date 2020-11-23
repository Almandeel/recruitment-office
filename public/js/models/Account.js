function Account(){
    this.id = null;
    this.number = null;
    this.name = null;
    this.type = null;
    this.side = null;
    this.mainId = null;
    this.mainName = null;
    this.mainType = null;
    this.finalId = null;
    this.finalName = null;
    this.action = null;
    this.method = null;
    this.balance = 0;
    this.view = 'create';
    // this.defaults = @json(\Modules\Accounting\Models\Account::DEFAULTS);
    // this.isDefault = function(){
    //     return this.defaults.includes(this.id);
    // };
    // this.isRoot = function(){
    //     return this.id <= 6;
    // };
    // this.isPrimary = function(){
    //     return this.type == {{ \Modules\Accounting\Models\Account::TYPE_PRIMARY }};
    // };
    // this.mainIsPrimary = function(){
    //     return this.mainType == {{ \Modules\Accounting\Models\Account::TYPE_PRIMARY }};
    // };
}