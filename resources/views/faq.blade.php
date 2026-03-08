@extends('layouts.app')

@section('content')
@php $reg = '/https?:\/\/[^\s]+/'; @endphp
  <div class="container py-5 faq-page">
    <div class="row">
      <div class="col-lg-10 mx-auto">

        <h1 class="faq-title mb-3">よくある質問</h1>
        <p class="faq-lead mb-4">
          ご注文やお届けに関して、よくいただくご質問をまとめました。<br class="d-none d-md-block">
          気になる項目をタップ（クリック）してご覧ください。
        </p>

        @foreach ($taxonomies as $taxonomy)
          @if ($taxonomy->articles->count() > 0)
            <section class="faq-category-block mb-4">
              <h2 class="faq-category-title h4 mb-3">
                {{ $taxonomy->category }}
              </h2>

              <div class="accordion faq-accordion" id="faq-accordion-{{ $taxonomy->id }}">
                @foreach ($taxonomy->articles as $article)
                  @if ($article->publish_id == 1)
                    <div class="card faq-card mb-2">
                      <div class="card-header p-0" id="heading-{{ $article->id }}">
                        <button
                          class="btn btn-link btn-block text-left faq-question collapsed"
                          type="button"
                          data-toggle="collapse"
                          data-target="#faq-{{ $article->id }}"
                          aria-expanded="false"
                          aria-controls="faq-{{ $article->id }}"
                        >
                          <span class="faq-q-label">Q</span>
                          <span class="faq-q-text">{{ $article->question }}</span>
                          <span class="faq-toggle-icon"></span>
                        </button>
                      </div>

                      <div
                        id="faq-{{ $article->id }}"
                        class="collapse"
                        aria-labelledby="heading-{{ $article->id }}"
                        data-parent="#faq-accordion-{{ $taxonomy->id }}"
                      >
                        <div class="card-body faq-answer">
                          <span class="faq-a-label">A</span>
                          <div class="faq-a-text">
                            {!! nl2br(preg_replace($reg, '<a href="$0" target="_blank" rel="noopener noreferrer">$0</a>', e($article->answer))) !!}
                          </div>
                        </div>
                      </div>
                    </div>
                  @endif
                @endforeach
              </div>
            </section>
          @endif
        @endforeach

      </div>
    </div>

  <style>
    /* ===============================
      FAQ 全体レイアウト
    ================================ */
    .faq-page {
      background-color: #f8f9fa;
      padding-top: 3rem;
      padding-bottom: 3rem;
      margin-top: 2rem;
      margin-bottom: 2rem;
      border-radius: 0.5rem;
    }

    .faq-title {
      font-size: 1.6rem;
      font-weight: 600;
      letter-spacing: 0.03em;
    }

    @media (min-width: 768px) {
      .faq-title {
        font-size: 1.9rem;
      }
    }

    .faq-lead {
      font-size: 0.95rem;
      color: #6c757d;
      line-height: 1.7;
    }

    /* カテゴリブロック */
    .faq-category-block:not(:last-child) {
      border-bottom: 1px solid #e5e5e5;
      padding-bottom: 1.5rem;
    }

    .faq-category-title {
      font-weight: 600;
      display: inline-flex;
      align-items: center;
      gap: 0.5rem;
    }

    .faq-category-title::before {
      content: "";
      display: inline-block;
      width: 4px;
      height: 1.3em;
      border-radius: 99px;
      background: linear-gradient(135deg, #D16A8C, #F3B4C8);
    }

    /* ===============================
      質問カード（Q&A）
    ================================ */
    .faq-card {
      border: none;
      border-radius: 0.6rem;
      box-shadow: 0 4px 14px rgba(209, 106, 140, 0.12);
      overflow: hidden;
      background-color: #ffffff;
    }

    .faq-card + .faq-card {
      margin-top: 0.35rem;
    }

    .faq-question {
      color: #212529 !important;  
      text-decoration: none !important;
      display: flex;
      align-items: center;
      width: 100%;
      padding: 0.9rem 1.15rem;
    }

    .faq-question:hover,
    .faq-question:focus {
      color: #212529 !important;
      background-color: #fde6ee;
    }

    .faq-q-label {
      flex: 0 0 auto;
      width: 26px;
      height: 26px;
      border-radius: 50%;
      background: #D16A8C;
      color: #fff;
      font-size: 0.75rem;
      font-weight: 700;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      margin-top: 0.1rem;
      flex: 0 0 auto;
    }

    .faq-q-text {
      flex: 1 1 auto;
      margin-left: 0.6rem; 
      font-size: 1.10rem;
      line-height: 1.6;
      font-weight: 500;
      color: #212529 !important;
    }

    .faq-toggle-icon {
      flex: 0 0 auto;
      margin-left: auto;
      font-size: 1.1rem;
      line-height: 1;
      align-self: center;
    }

    .faq-toggle-icon::before {
      content: "＋";
      display: inline-block;
      transition: transform 0.2s ease;
    }

    .faq-question[aria-expanded="true"] .faq-toggle-icon::before {
      content: "－";
      transform: rotate(0deg);
    }

    /* ===============================
      回答エリア
    ================================ */
    .faq-answer {
      position: relative;
      padding: 0.9rem 1.15rem 1rem 3.1rem;
      font-size: 0.95rem;
      line-height: 1.8;
      color: #495057;
      border-top: 1px solid #f1f3f5;
      background-color: #ffffff;
    }

    .faq-a-label {
      position: absolute;
      left: 1.15rem;
      top: 0.95rem;
      width: 26px;
      height: 26px;
      border-radius: 50%;
      background: #C8577C;
      color: #fff;
      font-size: 0.75rem;
      font-weight: 700;
      display: inline-flex;
      align-items: center;
      justify-content: center;
    }

    .faq-a-text p {
      margin-bottom: 0.65rem;
    }

    .faq-a-text p:last-child {
      margin-bottom: 0;
    }

    /* PC 少しゆったりめ */
    @media (min-width: 768px) {
      .faq-question {
        padding: 1rem 1.25rem;
      }
      .faq-answer {
        padding: 1rem 1.25rem 1.1rem 3.3rem;
      }
    }

    /* スマホでも長文が折り返されるようにする */
  .faq-question {
    white-space: normal !important;  /* ← ここで .btn の nowrap を打ち消す */
  }

  /* 念のためテキスト側も明示しておく */
  .faq-q-text {
    white-space: normal;
    padding-right: 0.25rem;          /* ＋マークとの距離を少しだけ確保（任意） */
  }
  </style>

  </div>
@stop
