function queryParamsLeadForms(params) {
    console.log('Query Params:', params);
    return {
        search: params.search,
        limit: params.limit,
        offset: params.offset,
        sort: params.sort,
        order: params.order
    };
}
