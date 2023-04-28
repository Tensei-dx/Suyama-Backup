<template>
    <div>
        <div class="container pt-3 text-center align-middle">
            <div class="header-title text-center align-middle font-weight-normal">
                {{$t('termsAndCondition.title')}}
            </div>
            <div class="content-container text-justify pt-3" id="termsOfUse">
                <div class="px-md-4">
                    <ul class="list-unstyled">
                        <li class="text-center pb-3">
                            <h4>{{ $t('termsAndConditions.termsOfUse') }}</h4>
                        </li>
                        <li>{{ $t('termsAndConditions.introduction') }}</li>
                        <li class="pt-3">
                            <h5>{{ $t('termsAndConditions.safety.title') }}</h5>
                        </li>

                        <li class="row"><span class="col-auto">1）</span><span
                                  class="col-sm-11">{{ $t('termsAndConditions.safety.1') }}</span></li>
                        <li class="row"><span class="col-auto">2）</span><span
                                  class="col-sm-11">{{ $t('termsAndConditions.safety.2') }}</span></li>
                        <li class="row"><span class="col-auto">3）</span><span
                                  class="col-sm-11">{{ $t('termsAndConditions.safety.3') }}</span></li>
                        <li class="row"><span class="col-auto">4）</span><span
                                  class="col-sm-11">{{ $t('termsAndConditions.safety.4') }}</span></li>
                        <li class="row"><span class="col-auto">5）</span><span
                                  class="col-sm-11">{{ $t('termsAndConditions.safety.5') }}</span></li>
                        <li class="row"><span class="col-auto">6）</span><span
                                  class="col-sm-11">{{ $t('termsAndConditions.safety.6') }}</span>
                        </li>
                        <li class="row"><span class="col-auto">7）</span><span
                                  class="col-sm-11">{{ $t('termsAndConditions.safety.7') }}</span></li>
                        <li class="row"><span class="col-auto">8）</span><span
                                  class="col-sm-11">{{ $t('termsAndConditions.safety.8') }}</span></li>

                        <li class="pt-3">
                            <h5>{{ $t('termsAndConditions.handling.title') }}</h5>
                        </li>

                        <li class="row"><span class="col-auto">1）</span><span
                                  class="col-sm-11">{{ $t('termsAndConditions.handling.1') }}</span>
                        </li>
                        <li class="row"><span class="col-auto">2）</span><span
                                  class="col-sm-11">{{ $t('termsAndConditions.handling.2') }}</span>
                        </li>

                        <li class="pt-3">
                            <h5>{{ $t('termsAndConditions.payment.title') }}</h5>
                        </li>

                        <li class="row"><span class="col-auto">1）</span><span
                                  class="col-sm-11">{{ $t('termsAndConditions.payment.1') }}</span>
                        </li>
                        <li class="row"><span class="col-auto">2）</span><span
                                  class="col-sm-11">{{ $t('termsAndConditions.payment.2') }}</span>
                        </li>

                        <li class="pt-3">
                            <h5>{{ $t('termsAndConditions.actions.title') }}</h5>
                        </li>

                        <li class="row"><span class="col-auto">1）</span><span
                                  class="col-sm-11">{{ $t('termsAndConditions.actions.1') }}</span></li>
                        <li class="row px-3"><span class="col-auto">Ａ）</span><span
                                  class="col-sm-11">{{ $t('termsAndConditions.actions.a') }}</span></li>
                        <li class="row px-3"><span class="col-auto">Ｂ）</span><span
                                  class="col-sm-11">{{ $t('termsAndConditions.actions.b') }}</span></li>
                        <li class="row px-3"><span class="col-auto">Ｃ）</span><span
                                  class="col-sm-11">{{ $t('termsAndConditions.actions.c') }}</span></li>
                        <li class="row px-3"><span class="col-auto">Ｄ）</span><span class="col-sm-11">{{ $t('termsAndConditions.actions.d') }}</span>
                        </li>
                        <li class="row px-3"><span class="col-auto">Ｅ）</span><span
                                  class="col-sm-11">{{ $t('termsAndConditions.actions.e') }}</span></li>
                        <li class="row px-3"><span class="col-auto">Ｆ）</span><span
                                  class="col-sm-11">{{ $t('termsAndConditions.actions.f') }}</span></li>

                        <li class="row"><span class="col-auto">2）</span><span
                                  class="col-sm-11">{{ $t('termsAndConditions.actions.2') }}</span></li>
                        <li class="row"><span class="col-auto">3）</span><span
                                  class="col-sm-11">{{ $t('termsAndConditions.actions.3') }}</span></li>
                        <li class="row"><span class="col-auto">4）</span><span
                                  class="col-sm-11">{{ $t('termsAndConditions.actions.4') }}</span></li>
                        <li class="row"><span class="col-auto">5）</span><span
                                  class="col-sm-11">{{ $t('termsAndConditions.actions.5') }}</span></li>
                        <li class="row"><span class="col-auto">6）</span><span
                                  class="col-sm-11">{{ $t('termsAndConditions.actions.6') }}</span></li>
                        <li class="row"><span class="col-auto">7）</span><span
                                  class="col-sm-11">{{ $t('termsAndConditions.actions.7') }}</span></li>
                        <li class="row"><span class="col-auto">8）</span><span
                                  class="col-sm-11">{{ $t('termsAndConditions.actions.8') }}</span>
                        </li>
                        <li class="row"><span class="col-auto">9）</span><span
                                  class="col-sm-11">{{ $t('termsAndConditions.actions.9') }}</span></li>
                        <li class="row"><span class="col-auto pr-2">10）</span><span
                                  class="col-sm-11">{{ $t('termsAndConditions.actions.10') }}</span>
                        </li>
                        <li class="row"><span class="col-auto pr-2">11）</span><span
                                  class="col-sm-11">{{ $t('termsAndConditions.actions.11') }}</span></li>

                        <li class="pt-1 text-danger">
                            <h6>{{ $t('termsAndConditions.actions.footer') }}</h6>
                        </li>

                        <li class="text-center pt-4">
                            <h4>{{ $t('termsAndConditions.termsOfHotel') }}</h4>
                        </li>

                        <li>
                            <h6>{{ $t('termsAndConditions.article1.header') }}</h6>
                        </li>
                        <li>
                            <h5>{{ $t('termsAndConditions.article1.title') }}</h5>
                        </li>
                        <li class="row"><span class="header-num col-auto">1</span>
                            <span class="col-sm-11">{{ $t('termsAndConditions.article1.1') }}</span>
                        </li>
                        <li class="row"><span class="header-num col-auto">2</span>
                            <span class="col-sm-11">{{ $t('termsAndConditions.article1.2') }}</span>
                        </li>

                        <li class="pt-2">
                            <h6>{{ $t('termsAndConditions.article2.header') }}</h6>
                        </li>
                        <li class="">
                            <h5>{{ $t('termsAndConditions.article2.title') }}</h5>
                        </li>
                        <li class="row"><span class="header-num col-auto">1</span><span
                                  class="col-sm-11"></span>{{ $t('termsAndConditions.article2.1.content') }}</li>
                        <li class="row px-3"><span class="col-auto">1）</span>
                            <span class="col-sm-11">{{ $t('termsAndConditions.article2.1.1') }}</span>
                        </li>
                        <li class="row px-3"><span class="col-auto">2）</span>
                            <span class="col-sm-11">{{ $t('termsAndConditions.article2.1.2') }}</span>
                        </li>
                        <li class="row px-3"><span class="col-auto">3）</span>
                            <span class="col-sm-11">{{ $t('termsAndConditions.article2.1.3') }}</span>
                        </li>
                        <li><span class="header-num">2</span>
                            <span class="col-sm-11">{{ $t('termsAndConditions.article2.2') }}</span>
                        </li>

                        <li class="pt-2">
                            <h6>{{ $t('termsAndConditions.article3.header') }}</h6>
                        </li>
                        <li>
                            <h5>{{ $t('termsAndConditions.article3.title') }}</h5>
                        </li>
                        <li class="row"><span class="header-num col-auto">1</span><span
                                  class="col-sm-11">{{ $t('termsAndConditions.article3.1') }}</span>
                        </li>
                        <li class="row"><span class="header-num col-auto">2</span><span
                                  class="col-sm-11">{{ $t('termsAndConditions.article3.2') }}</span>
                        </li>
                        <li class="row"><span class="header-num col-auto">3</span><span
                                  class="col-sm-11">{{ $t('termsAndConditions.article3.3') }}</span>
                        </li>
                        <li class="row"><span class="header-num col-auto">4</span><span
                                  class="col-sm-11">{{ $t('termsAndConditions.article3.4') }}</span>
                        </li>

                        <li class="pt-2">
                            <h6>{{ $t('termsAndConditions.article4.header') }}</h6>
                        </li>
                        <li>
                            <h5>{{ $t('termsAndConditions.article4.title') }}</h5>
                        </li>
                        <li class="row"><span class="header-num col-auto">1</span><span
                                  class="col-sm-11">{{ $t('termsAndConditions.article4.1') }}</span>
                        </li>
                        <li class="row"><span class="header-num col-auto">2</span><span
                                  class="col-sm-11">{{ $t('termsAndConditions.article4.2') }}</span>
                        </li>

                        <li class="pt-2">
                            <h6>{{ $t('termsAndConditions.article5.header') }}</h6>
                        </li>
                        <li>
                            <h5>{{ $t('termsAndConditions.article5.title') }}</h5>
                        </li>
                        <li class="row"><span class="header-num col-auto">1</span><span
                                  class="col-sm-11">{{ $t('termsAndConditions.article5.1.content') }}</span></li>
                        <li class="row px-3"><span class="col-auto">1）</span><span
                                  class="col-sm-11">{{ $t('termsAndConditions.article5.1.1') }}</span></li>
                        <li class="row px-3"><span class="col-auto">2）</span><span
                                  class="col-sm-11">{{ $t('termsAndConditions.article5.1.2') }}</span></li>
                        <li class="row px-3"><span class="col-auto">3）</span><span
                                  class="col-sm-11">{{ $t('termsAndConditions.article5.1.3') }}</span>
                        </li>
                        <li class="row px-3"><span class="col-auto">4）</span><span
                                  class="col-sm-11">{{ $t('termsAndConditions.article5.1.4') }}</span></li>
                        <li class="row px-3"><span class="col-auto">5）</span><span
                                  class="col-sm-11">{{ $t('termsAndConditions.article5.1.5') }}</span>
                        </li>
                        <li class="row px-3"><span class="col-auto">6）</span><span
                                  class="col-sm-11">{{ $t('termsAndConditions.article5.1.6') }}</span></li>
                        <li class="row px-3"><span class="col-auto">7）</span><span
                                  class="col-sm-11">{{ $t('termsAndConditions.article5.1.7') }}</span>
                        </li>
                        <li class="row px-3"><span class="col-auto">8）</span><span
                                  class="col-sm-11">{{ $t('termsAndConditions.article5.1.8') }}</span></li>
                        <li class="row px-3"><span class="col-auto">9）</span><span
                                  class="col-sm-11">{{ $t('termsAndConditions.article5.1.9') }}</span>
                        </li>
                        <li class="row px-3"><span class="col-auto pr-2">10）</span><span
                                  class="col-sm-11">{{ $t('termsAndConditions.article5.1.10') }}</span></li>
                        <li class="row px-3"><span class="col-auto pr-2">11）</span><span
                                  class="col-sm-11">{{ $t('termsAndConditions.article5.1.11') }}</span></li>
                        <li class="row px-3"><span class="col-auto pr-2">12）</span><span
                                  class="col-sm-11">{{ $t('termsAndConditions.article5.1.12') }}</span></li>
                        <li class="row px-3"><span class="col-auto pr-2">13）</span><span
                                  class="col-sm-11">{{ $t('termsAndConditions.article5.1.13') }}</span></li>

                        <li class="pt-2">
                            <h6>{{ $t('termsAndConditions.article6.header') }}</h6>
                        </li>
                        <li>
                            <h5>{{ $t('termsAndConditions.article6.title') }}</h5>
                        </li>
                        <li class="row"><span class="header-num col-auto">1</span><span
                                  class="col-sm-11">{{ $t('termsAndConditions.article6.1') }}</span></li>
                        <li class="row"><span class="header-num col-auto">2</span><span
                                  class="col-sm-11">{{ $t('termsAndConditions.article6.2') }}</span>
                        </li>
                        <li class="row"><span class="header-num col-auto">3</span><span
                                  class="col-sm-11">{{ $t('termsAndConditions.article6.3') }}</span>
                        </li>

                        <li class="pt-2">
                            <h6>{{ $t('termsAndConditions.article7.header') }}</h6>
                        </li>
                        <li>
                            <h5>{{ $t('termsAndConditions.article7.title') }}</h5>
                        </li>
                        <li class="row"><span class="header-num col-auto">1</span><span
                                  class="col-sm-11">{{ $t('termsAndConditions.article7.1.content') }}</span></li>
                        <li class="row px-3"><span class="col-auto">1）</span><span
                                  class="col-sm-11">{{ $t('termsAndConditions.article7.1.1') }}</span></li>
                        <li class="row px-3"><span class="col-auto">2）</span><span
                                  class="col-sm-11">{{ $t('termsAndConditions.article7.1.2') }}</span></li>
                        <li class="row"><span class="header-num col-auto">2</span><span
                                  class="col-sm-11">{{ $t('termsAndConditions.article7.2') }}</span>
                        </li>

                        <li class="pt-2">
                            <h6>{{ $t('termsAndConditions.article8.header') }}</h6>
                        </li>
                        <li>
                            <h5>{{ $t('termsAndConditions.article8.title') }}</h5>
                        </li>
                        <li class="row"><span class="header-num col-auto">1</span><span
                                  class="col-sm-11">{{ $t('termsAndConditions.article8.1.content') }}</span></li>
                        <li class="row px-3"><span class="col-auto">1）</span><span
                                  class="col-sm-11">{{ $t('termsAndConditions.article8.1.1') }}</span></li>
                        <li class="row px-3"><span class="col-auto">2）</span><span
                                  class="col-sm-11">{{ $t('termsAndConditions.article8.1.2') }}</span></li>
                        <li class="row px-3"><span class="col-auto">3）</span><span class="col-sm-11">{{ $t('termsAndConditions.article8.1.3') }}</span>
                        </li>
                        <li class="row px-3"><span class="col-auto">4）</span><span
                                  class="col-sm-11">{{ $t('termsAndConditions.article8.1.4') }}</span></li>
                        <li class="row"><span class="header-num col-auto">2</span><span
                                  class="col-sm-11">{{ $t('termsAndConditions.article8.2') }}</span>
                        </li>

                        <li class="pt-2">
                            <h6>{{ $t('termsAndConditions.article9.header') }}</h6>
                        </li>
                        <li>
                            <h5>{{ $t('termsAndConditions.article9.title') }}</h5>
                        </li>
                        <li class="row"><span class="header-num col-auto">1</span><span
                                  class="col-sm-11">{{ $t('termsAndConditions.article9.1') }}</span>
                        </li>
                        <li class="row"><span class="header-num col-auto">2</span><span
                                  class="col-sm-11">{{ $t('termsAndConditions.article9.2.content') }}</span>
                        </li>
                        <li class="row px-3"><span class="col-auto">1）</span><span
                                  class="col-sm-11">{{ $t('termsAndConditions.article9.2.1') }}</span></li>
                        <li class="row px-3"><span class="col-auto">2）</span><span
                                  class="col-sm-11">{{ $t('termsAndConditions.article9.2.2') }}</span></li>
                        <li class="row px-3"><span class="col-auto">3）</span><span
                                  class="col-sm-11">{{ $t('termsAndConditions.article9.2.3') }}</span></li>

                        <li class="pt-2">
                            <h6>{{ $t('termsAndConditions.article10.header') }}</h6>
                        </li>
                        <li>
                            <h5>{{ $t('termsAndConditions.article10.title') }}</h5>
                        </li>
                        <li class="px-3">{{ $t('termsAndConditions.article10.content') }}</li>

                        <li class="pt-2">
                            <h6>{{ $t('termsAndConditions.article11.header') }}</h6>
                        </li>
                        <li>
                            <h5>{{ $t('termsAndConditions.article11.title') }}</h5>
                        </li>
                        <li class="row"><span class="header-num col-auto">1</span><span
                                  class="col-sm-11">{{ $t('termsAndConditions.article11.1') }}</span>
                        </li>
                        <li class="row"><span class="header-num col-auto">2</span><span
                                  class="col-sm-11">{{ $t('termsAndConditions.article11.2') }}</span>
                        </li>

                        <li class="pt-2">
                            <h6>{{ $t('termsAndConditions.article12.header') }}</h6>
                        </li>
                        <li>
                            <h5>{{ $t('termsAndConditions.article12.title') }}</h5>
                        </li>
                        <li class="row"><span class="header-num col-auto">1</span><span
                                  class="col-sm-11">{{ $t('termsAndConditions.article12.1') }}</span></li>
                        <li class="row"><span class="header-num col-auto">2</span><span
                                  class="col-sm-11">{{ $t('termsAndConditions.article12.2') }}</span>
                        </li>
                        <li class="row"><span class="header-num col-auto">3</span><span
                                  class="col-sm-11">{{ $t('termsAndConditions.article12.3') }}</span>
                        </li>

                        <li class="pt-2">
                            <h6>{{ $t('termsAndConditions.article13.header') }}</h6>
                        </li>
                        <li>
                            <h5>{{ $t('termsAndConditions.article13.title') }}</h5>
                        </li>
                        <li class="row"><span class="header-num col-auto">1</span><span
                                  class="col-sm-11">{{ $t('termsAndConditions.article13.1') }}</span>
                        </li>
                        <li class="row"><span class="header-num col-auto">2</span><span
                                  class="col-sm-11">{{ $t('termsAndConditions.article13.2') }}</span></li>

                        <li class="pt-2">
                            <h6>{{ $t('termsAndConditions.article14.header') }}</h6>
                        </li>
                        <li>
                            <h5>{{ $t('termsAndConditions.article14.title') }}</h5>
                        </li>
                        <li class="row"><span class="header-num col-auto">1</span><span
                                  class="col-sm-11">{{ $t('termsAndConditions.article14.1') }}</span>
                        </li>
                        <li class="row"><span class="header-num col-auto">2</span><span
                                  class="col-sm-11">{{ $t('termsAndConditions.article14.2') }}</span>
                        </li>

                        <li class="pt-2">
                            <h6>{{ $t('termsAndConditions.article15.header') }}</h6>
                        </li>
                        <li>
                            <h5>{{ $t('termsAndConditions.article15.title') }}</h5>
                        </li>
                        <li class="row"><span class="header-num col-auto">1</span><span
                                  class="col-sm-11">{{ $t('termsAndConditions.article15.1') }}</span>
                        </li>
                        <li class="row"><span class="header-num col-auto">2</span><span
                                  class="col-sm-11">{{ $t('termsAndConditions.article15.2') }}</span>
                        </li>

                        <li class="pt-2">
                            <h6>{{ $t('termsAndConditions.article16.header') }}</h6>
                        </li>
                        <li>
                            <h5>{{ $t('termsAndConditions.article16.title') }}</h5>
                        </li>
                        <li class="row"><span class="header-num col-auto">1</span><span
                                  class="col-sm-11">{{ $t('termsAndConditions.article16.1') }}</span>
                        </li>
                        <li class="row"><span class="header-num col-auto">2</span><span
                                  class="col-sm-11">{{ $t('termsAndConditions.article16.2') }}</span>
                        </li>
                        <li class="row"><span class="header-num col-auto">3</span><span
                                  class="col-sm-11">{{ $t('termsAndConditions.article16.3') }}</span>
                        </li>

                        <li class="pt-2">
                            <h6>{{ $t('termsAndConditions.article17.header') }}</h6>
                        </li>
                        <li>
                            <h5>{{ $t('termsAndConditions.article17.title') }}</h5>
                        </li>
                        <li class="px-3">
                            {{ $t('termsAndConditions.article17.content') }}
                        </li>

                        <li class="pt-2">
                            <h6>{{ $t('termsAndConditions.article18.header') }}</h6>
                        </li>
                        <li>
                            <h5>{{ $t('termsAndConditions.article18.title') }}</h5>
                        </li>
                        <li class="px-3">
                            {{ $t('termsAndConditions.article18.content') }}
                        </li>

                        <li class="next-line px-3" v-if="$i18n.locale === 'en'">
                            <div class="row">
                                <div class="col-auto">
                                    <div>{{ $t('termsAndConditions.table1.header1') }}</div>
                                    <div>{{ $t('termsAndConditions.table1.header2') }}</div>
                                    <div class="next-line">
                                        <table class="table first-table">
                                            <thead>
                                                <tr>
                                                    <th colspan="2">{{ ' ' }} </th>
                                                    <th scope="col">{{ $t('termsAndConditions.table1.thead.th') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <th rowspan="3">{{ $t('termsAndConditions.table1.tbody.tr1.th') }}</th>
                                                    <td class="row-first align-middle">{{ $t('termsAndConditions.table1.tbody.tr1.td1') }}</td>
                                                    <td class="row-first align-middle">{{ $t('termsAndConditions.table1.tbody.tr1.td2') }}</td>
                                                </tr>
                                                <tr>
                                                    <td>{{ $t('termsAndConditions.table1.tbody.tr2.td1') }}</td>
                                                    <td>{{ $t('termsAndConditions.table1.tbody.tr2.td2') }}</td>
                                                </tr>
                                                <tr>
                                                    <td>{{ $t('termsAndConditions.table1.tbody.tr3.td1') }}</td>
                                                    <td>{{ $t('termsAndConditions.table1.tbody.tr3.td2') }}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div style="margin-bottom: 1rem">{{ $t('termsAndConditions.table1.remarks') }}</div>
                                </div>
                                <div class="col-auto">
                                    <div>{{ $t('termsAndConditions.table2.header1') }}</div>
                                    <div>{{ $t('termsAndConditions.table2.header2') }}</div>
                                    <div class="next-line">
                                        <table class="table second-table">
                                            <thead>
                                                <tr>
                                                    <th>{{ ' ' }} </th>
                                                    <th scope="col">{{ $t('termsAndConditions.table2.thead.tr1.th1') }}</th>
                                                    <th rowspan="2" class="align-middle">{{ $t('termsAndConditions.table2.thead.tr1.th2') }}</th>
                                                    <th rowspan="2" class="align-middle">{{ $t('termsAndConditions.table2.thead.tr1.th3') }}</th>
                                                    <th rowspan="2" class="align-middle">{{ $t('termsAndConditions.table2.thead.tr1.th4') }}</th>
                                                    <th rowspan="2" class="align-middle">{{ $t('termsAndConditions.table2.thead.tr1.th5') }}</th>
                                                    <th rowspan="2" class="align-middle">{{ $t('termsAndConditions.table2.thead.tr1.th6') }}</th>
                                                </tr>
                                                <tr>
                                                    <th>{{ $t('termsAndConditions.table2.thead.tr2.th1') }}</th>
                                                    <td>{{ ' ' }} </td>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <th>{{ $t('termsAndConditions.table2.tbody.tr1.th') }}</th>
                                                    <td>{{ $t('termsAndConditions.table2.tbody.tr1.td1') }}</td>
                                                    <td>100％</td>
                                                    <td>100％</td>
                                                    <td>100％</td>
                                                    <td>{{ ' ' }} </td>
                                                    <td>{{ ' ' }} </td>
                                                </tr>
                                                <tr>
                                                    <th rowspan="2" class="align-middle">{{ $t('termsAndConditions.table2.tbody.tr2.th') }}</th>
                                                    <td>{{ $t('termsAndConditions.table2.tbody.tr2.td1') }}</td>
                                                    <td>100％</td>
                                                    <td>100％</td>
                                                    <td>100％</td>
                                                    <td>10％</td>
                                                    <td>{{ ' ' }} </td>
                                                </tr>
                                                <tr>
                                                    <td>{{ $t('termsAndConditions.table2.tbody.tr3.td') }}</td>
                                                    <td>100％</td>
                                                    <td>100％</td>
                                                    <td>100％</td>
                                                    <td>20％</td>
                                                    <td>10％</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </li>

                        <li class="next-line px-3" v-else>
                            <div class="row">
                                <div class="col-auto">
                                    <div>{{ $t('termsAndConditions.table1.header1') }}</div>
                                    <div>{{ $t('termsAndConditions.table1.header2') }}</div>
                                    <div class="next-line">
                                        <table class="table first-table">
                                            <thead>
                                                <tr>
                                                    <th colspan="2">{{ ' ' }} </th>
                                                    <th scope="col">{{ $t('termsAndConditions.table1.thead.th') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <th rowspan="3">{{ $t('termsAndConditions.table1.tbody.tr1.th') }}</th>
                                                    <td class="row-first align-middle">{{ $t('termsAndConditions.table1.tbody.tr1.td1') }}</td>
                                                    <td class="row-first align-middle">{{ $t('termsAndConditions.table1.tbody.tr1.td2') }}</td>
                                                </tr>
                                                <tr>
                                                    <td>{{ $t('termsAndConditions.table1.tbody.tr2.td1') }}</td>
                                                    <td>{{ $t('termsAndConditions.table1.tbody.tr2.td2') }}</td>
                                                </tr>
                                                <tr>
                                                    <td>{{ $t('termsAndConditions.table1.tbody.tr3.td1') }}</td>
                                                    <td>{{ $t('termsAndConditions.table1.tbody.tr3.td2') }}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div style="margin-bottom: 1rem">{{ $t('termsAndConditions.table1.remarks') }}</div>
                                </div>
                                <div class="col-auto">
                                    <div>{{ $t('termsAndConditions.table2.header1') }}</div>
                                    <div>{{ $t('termsAndConditions.table2.header2') }}</div>
                                    <div class="next-line">
                                        <table class="table second-table">
                                            <thead>
                                                <tr>
                                                    <th>{{ ' ' }} </th>
                                                    <th scope="col">{{ $t('termsAndConditions.table2.thead.tr1.th1') }}</th>
                                                    <th rowspan="2" class="align-middle">{{ $t('termsAndConditions.table2.thead.tr1.th2') }}</th>
                                                    <th rowspan="2" class="align-middle">{{ $t('termsAndConditions.table2.thead.tr1.th3') }}</th>
                                                    <th rowspan="2" class="align-middle">{{ $t('termsAndConditions.table2.thead.tr1.th4') }}</th>
                                                    <th rowspan="2" class="align-middle">{{ $t('termsAndConditions.table2.thead.tr1.th5') }}</th>
                                                    <th rowspan="2" class="align-middle">{{ $t('termsAndConditions.table2.thead.tr1.th6') }}</th>
                                                </tr>
                                                <tr>
                                                    <th>{{ $t('termsAndConditions.table2.thead.tr2.th1') }}</th>
                                                    <td>{{ ' ' }} </td>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <th>{{ $t('termsAndConditions.table2.tbody.tr1.th') }}</th>
                                                    <td>{{ $t('termsAndConditions.table2.tbody.tr1.td1') }}</td>
                                                    <td>100％</td>
                                                    <td>100％</td>
                                                    <td>100％</td>
                                                    <td>{{ ' ' }} </td>
                                                    <td>{{ ' ' }} </td>
                                                </tr>
                                                <tr>
                                                    <th rowspan="2" class="align-middle">{{ $t('termsAndConditions.table2.tbody.tr2.th') }}</th>
                                                    <td>{{ $t('termsAndConditions.table2.tbody.tr2.td1') }}</td>
                                                    <td>100％</td>
                                                    <td>100％</td>
                                                    <td>100％</td>
                                                    <td>10％</td>
                                                    <td>{{ ' ' }} </td>
                                                </tr>
                                                <tr>
                                                    <td>{{ $t('termsAndConditions.table2.tbody.tr3.td') }}</td>
                                                    <td>100％</td>
                                                    <td>100％</td>
                                                    <td>100％</td>
                                                    <td>20％</td>
                                                    <td>10％</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </li>

                        <li class="next-line">
                            <h6>{{ $t('termsAndConditions.safetyRemarks.title') }}</h6>
                        </li>
                        <li class="px-3">{{ $t('termsAndConditions.safetyRemarks.content') }}</li>
                        <li class="px-3">{{ $t('termsAndConditions.safetyRemarks.1') }}</li>
                        <li class="px-3">{{ $t('termsAndConditions.safetyRemarks.2') }}</li>
                        <li class="px-3">{{ $t('termsAndConditions.safetyRemarks.3') }}</li>
                        <li class="px-3">{{ $t('termsAndConditions.safetyRemarks.4') }}</li>
                    </ul>
                </div>
            </div>
            <div class="button-container">
                <a role="button" href="#" class="btn btn-primary btn-lg float-right"
                   :class="buttonStatus ? 'disabled' : ''" style="max-width: 110px; width: 90px; max-height:50px"
                   @click="goWelcome()"> {{$t('termsAndCondition.nxtBtn')}}</a>
                <div class="align-middle agree-container px-1" :class="termsDisabled ? 'disableDiv':''">
                    <label class="py-3"><input type="checkbox" style="margin-right: 15px"
                               v-model="isAgreed">{{$t('termsAndCondition.agree')}}</label>
                </div>
            </div>
        </div>
        <Footer class="bg-light-gray" :localeLang="this.locale"></Footer>
    </div>
</template>

<script>
import Footer from '../Guest/Footer/Footer.vue'
export default {
    components: {
        Footer,
    },
    data() {
        return {
            errors: '',
            locale: '',
            visibility: 'd-none',
            isButtonDisabled: true,
            isAgreed: false,
            termsDisabled: true,
        }
    },
    methods: {
        goWelcome() {
            window.location = 'welcome'
        },
        getSession() {
            axios.get('/getSession').then(response => {
                if (response.data.locale) {
                    this.locale = response.data.locale
                }
            })
            console.log('Nani')
        },
    },
    created() {
        this.getSession()
    },
    computed: {
        buttonStatus: function () {
            if (this.isAgreed) {
                return (this.isButtonDisabled = false)
            } else {
                return (this.isButtonDisabled = true)
            }
        },
    },
    mounted() {
        const self = this

        $('#termsOfUse').scroll(function () {
            var element = document.getElementById('termsOfUse')
            if (element.scrollHeight - element.scrollTop <= element.clientHeight) {
                // self.isAgreed = true;
                self.termsDisabled = false
            }
        })
    },
}
</script>

<style scoped>
.container {
    max-width: 1700px;
    height: 100% !important;
    width: 100% !important;
    margin: 0 auto !important;
}
.next-line {
    padding-top: 1rem;
}
.vertical-text {
    writing-mode: vertical-rl;
    text-orientation: mixed;
}
.header-num {
    font-size: 16px;
}
.header-title {
    margin-top: 50px;
    max-width: 1500px;
    margin: 0 auto;
    font-size: 50px;
    color: #fff;
    background-color: #0000007f;
    opacity: 1;
}
.content-container {
    max-width: 1400px;
    max-height: 700px !important;
    margin-top: 20px !important;
    margin: 0 auto;
    background-color: #fff;
    font-size: 13px;
    overflow-y: auto;
    padding-bottom: 5px;
}
.button-container {
    max-width: 1400px;
    max-height: auto;
    margin-top: 20px !important;
    margin: 0 auto;
}
.agree-container {
    background-color: #0000007f;
    color: #fff;
    height: 100px !important;
    display: inline;
    margin: 0 auto;
    padding: 17px 20px !important;
    border-radius: 10px;
    font-size: 16px;
}
.first-table {
    text-align: center !important;
    border: solid 1px #000 !important;
    max-width: 500px;
}
.first-table thead {
    border: solid 1px #000 !important;
}
.first-table tbody {
    border: solid 1px #000 !important;
}
.first-table thead th,
td {
    border: solid 1px #000 !important;
}
.first-table tbody th,
td {
    border: solid 1px #000 !important;
}
.first-table tbody th {
    width: 10px !important;
}
.first-table .row-first {
    height: 150px !important;
}
.second-table {
    text-align: center !important;
    border: solid 1px #000 !important;
}
.second-table thead {
    border: solid 1px #000 !important;
}
.second-table tbody {
    border: solid 1px #000 !important;
}
.second-table thead th,
td {
    border: solid 1px #000 !important;
}
.second-table tbody th,
td {
    border: solid 1px #000 !important;
}
.bg-light-gray {
    background-color: lightgray;
}
@media (max-width: 1400px) {
    .content-container {
        width: 90% !important;
    }
    .button-container {
        width: 90% !important;
    }
}
@media (max-height: 950px) {
    .content-container {
        max-height: 670px !important;
    }
}
@media (max-height: 900px) {
    .content-container {
        max-height: 620px !important;
    }
}
@media (max-height: 850px) {
    .content-container {
        max-height: 570px !important;
    }
}
@media (max-height: 800px) {
    .content-container {
        max-height: 520px !important;
    }
}
@media (max-height: 750px) {
    .content-container {
        max-height: 470px !important;
    }
}
@media (max-height: 700px) {
    .content-container {
        max-height: 420px !important;
    }
}
@media (max-height: 650px) {
    .content-container {
        max-height: 370px !important;
    }
}
@media (max-height: 600px) {
    .content-container {
        max-height: 320px !important;
    }
}
@media (max-height: 550px) {
    .content-container {
        max-height: 250px !important;
    }
}
@media (max-height: 500px) {
    .content-container {
        max-height: 230px !important;
    }
}
</style>>
