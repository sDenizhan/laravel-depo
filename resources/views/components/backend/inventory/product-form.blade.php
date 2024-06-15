<div class="row">
    <form method="post" action="{{ route('admin.inventory.store') }}">
        @csrf
        @method('POST')
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="repos mb-3">
                                <label for="repo_id" class="form-label">{{ __('Product Repos') }}<span class="text-danger">*</span> </label>
                                <select name="repo_id" id="repo_id" class="form-control @error('repo_id') is-invalid @enderror">
                                    @foreach($repos as $repo)
                                        <option value="{{ $repo->id }}">{{ $repo->name }}</option>
                                    @endforeach
                                </select>
                                @error('repo_id')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="price mb-3">
                                <label for="price" class="form-label">{{ __('Product Price') }}<span class="text-danger">*</span> </label>
                                <input type="text" class="form-control @error('price') is-invalid @enderror" id="price" name="price" value="{{ $product->price  }}">
                                @error('price')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="price mb-3">
                                <label for="quantity" class="form-label">{{ __('Quantity') }}<span class="text-danger">*</span> </label>
                                <input type="text" class="form-control @error('quantity') is-invalid @enderror" id="quantity" name="quantity" value="">
                                @error('quantity')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-3 currency">
                                <label for="currency" class="form-label">{{ __('Product Currency') }}<span class="text-danger">*</span></label>
                                @php
                                    $currencies = \App\Enums\Currency::toArray();
                                @endphp
                                <select name="currency" id="currency" class="form-control @error('currency') is-invalid @enderror">
                                    @foreach($currencies as $currency)
                                        <option value="{{ $currency->value }}"{{ $currency->value == $product->currency ? 'selected' : '' }}>{{ $currency->name }}</option>
                                    @endforeach
                                </select>
                                @error('currency')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <button type="submit" class="btn btn-sm btn-primary">{{ __('Save') }}</button>
                </div>
            </div>
        </div>
    </form>
</div>
