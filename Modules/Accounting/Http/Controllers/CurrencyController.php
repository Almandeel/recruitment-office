<?php

namespace Modules\Accounting\Http\Controllers;
use Modules\Accounting\Models\Currency;
use Illuminate\Http\Request;

class CurrencyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $currencies = Currency::all();

        return view('accounting::currencies/index', compact('currencies'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('accounting::currencies/create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate([
            // 'name' => 'required',
            // 'short' => 'required',
            'sample' => 'required | min:1| max:3|',
        ]);
        
        $currency = new Currency();
        $currency->sample = $request->sample;
        for($i = 0; $i < count(config('translatable.locales')); $i++) {
            $locale = config('translatable.locales')[$i];
            $currency_name = "name_" . $locale;
            $currency->setTranslation('name', $locale, $request->$currency_name);
            
            $currency_short = "short_" . $locale;
            $currency->setTranslation('short', $locale, $request->$currency_short);
        }
        
        
        $currency->save();
        // Account::create([
        //     'type' => Account::TYPE_CREDIT,
        //     'currency_id' => $currency->id,
        //     'group_id' => branch()->currentAssets()->id,
        // ]);

        if($request->next == 'save_new'){
            return redirect()->route('currencies.create')->with('success', __('accounting::global.create_success'));
        }
        else if($request->next == 'save_list'){
            return redirect()->route('currencies.index')->with('success', __('accounting::global.create_success'));
        }
        else if($request->next == 'save_show'){
            return redirect()->route('currencies.show', $currency->id)->with('success', __('accounting::global.create_success'));
        }

        return back()->with('error', __('accounting::global.create_fail'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Currency  $currency
     * @return \Illuminate\Http\Response
     */
    public function show(Currency $currency)
    {
        return view('accounting::currencies/show', compact('currency'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Currency  $currency
     * @return \Illuminate\Http\Response
     */
    public function edit(Currency $currency)
    {
        return view('accounting::currencies/edit', compact('currency'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Currency  $currency
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Currency $currency)
    {
        
        request()->validate([
            // 'name' => 'required',
            // 'short' => 'required',
            'sample' => 'required | min:1| max:3|',
        ]);
        
        
        $currency->sample = $request->sample;
        for($i = 0; $i < count(config('translatable.locales')); $i++) {
            $locale = config('translatable.locales')[$i];
            $currency_name = "name_" . $locale;
            $currency->setTranslation('name', $locale, $request->$currency_name);
            
            $currency_short = "short_" . $locale;
            $currency->setTranslation('short', $locale, $request->$currency_short);
        }
        
        
        $currency->save();

        if($request->next == 'save_edit'){
            return redirect()->route('currencies.edit', [$currency->id])->with('success', __('accounting::global.update_success'));
        }
        else if($request->next == 'save_list'){
            return redirect()->route('currencies.index')->with('success', __('accounting::global.update_success'));
        }
        else if($request->next == 'save_show'){
            return redirect()->route('currencies.show', [$currency->id])->with('success', __('accounting::global.update_success'));
        }

        return back()->with('error', __('accounting::global.update_fail'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Currency  $currency
     * @return \Illuminate\Http\Response
     */
    public function destroy(Currency $currency)
    {
        $currency->delete();
        return redirect()->route('currencies.index')->with('success', __('accounting::global.delete_success'));
    }
}
