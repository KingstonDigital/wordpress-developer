<?php
/**
 * Register custom Gutenberg block patterns and categories.
 */

if (!defined('ABSPATH')) {
	exit;
}

function kdm_lb_register_patterns()
{
	// Register the Hero Pattern
	register_block_pattern(
		'kdm-landing-blocks/hero',
		array(
			'title' => __('KDM Landing Hero', 'kdm-landing-blocks'),
			'description' => _x('A hero section with a signup form.', 'Block pattern description', 'kdm-landing-blocks'),
			'categories' => array('kdm-landing'),
			'content' => '
<!-- wp:group {"align":"full","className":"kdm-landing-pattern-wrapper","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull kdm-landing-pattern-wrapper">
	<!-- wp:html -->
	<section class="kdm-hero">
	  <div class="kdm-hero-content">
	    <div class="kdm-hero-eyebrow">Local SEO Toronto</div>
	    <h1 class="kdm-hero-title">Local <em>SEO</em><br>That Puts You<br>on the <strong>Map</strong></h1>
	    <p class="kdm-hero-desc">Stop losing customers to competitors who rank higher on Google Maps. We help Toronto businesses dominate local search — from Downtown to East York — with strategies built around how your customers actually search.</p>
	    <div class="kdm-hero-actions">
	      <a href="#quote" class="kdm-btn-primary">Get My Local SEO Plan</a>
	      <a href="https://kingstondigital.ca/seo-services-for-toronto-businesses" class="kdm-btn-outline">← Back to SEO Services</a>
	    </div>
	  </div>
	  <div class="kdm-hero-form-wrap">
	    <div class="kdm-hero-form">
	      <h3>Speak with a Local SEO Expert</h3>
	      <p>Free strategy call. No pressure, no obligations.</p>
	      <form>
	        <div class="kdm-form-row">
	          <div class="kdm-form-group">
	            <label>First Name</label>
	            <input type="text" placeholder="Jane" />
	          </div>
	          <div class="kdm-form-group">
	            <label>Last Name</label>
	            <input type="text" placeholder="Smith" />
	          </div>
	        </div>
	        <div class="kdm-form-row">
	          <div class="kdm-form-group">
	            <label>Email</label>
	            <input type="email" placeholder="jane@company.com" />
	          </div>
	          <div class="kdm-form-group">
	            <label>Phone</label>
	            <input type="tel" placeholder="416-555-0100" />
	          </div>
	        </div>
	        <div class="kdm-form-row">
	          <div class="kdm-form-group kdm-form-full">
	            <label>Message</label>
	            <textarea placeholder="Tell us about your business and goals…"></textarea>
	          </div>
	        </div>
	        <button type="submit" class="kdm-form-submit">Start Your Free Consultation →</button>
	      </form>
	    </div>
	  </div>
	</section>
	<!-- /wp:html -->
</div>
<!-- /wp:group -->'
		)
	);

	// Register the Intro Band Pattern
	register_block_pattern(
		'kdm-landing-blocks/intro-band',
		array(
			'title' => __('KDM Intro Band', 'kdm-landing-blocks'),
			'description' => _x('Simple intro band with centered text.', 'Block pattern description', 'kdm-landing-blocks'),
			'categories' => array('kdm-landing'),
			'content' => '
<!-- wp:group {"align":"full","className":"kdm-landing-pattern-wrapper","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull kdm-landing-pattern-wrapper">
	<!-- wp:html -->
	<section class="kdm-intro-band">
	  <div class="kdm-section-label">What Is Local SEO?</div>
	  <h2>Rank Where It <em>Matters</em> Most</h2>
	  <p>Local SEO is the process of optimizing your online presence so customers in your area find you first — on Google Maps, in the Local Pack, and across neighbourhood-specific searches. For Toronto businesses, it\'s the difference between a full calendar and an empty one.</p>
	</section>
	<!-- /wp:html -->
</div>
<!-- /wp:group -->'
		)
	);

	// Register General Services Component
	register_block_pattern(
		'kdm-landing-blocks/service-section',
		array(
			'title' => __('KDM Service Section (Default)', 'kdm-landing-blocks'),
			'description' => _x('Service section with left content and right mockup card.', 'Block pattern description', 'kdm-landing-blocks'),
			'categories' => array('kdm-landing'),
			'content' => '
<!-- wp:group {"align":"full","className":"kdm-landing-pattern-wrapper","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull kdm-landing-pattern-wrapper">
	<!-- wp:html -->
	<section class="kdm-service-section kdm-alt-bg">
	  <div class="kdm-service-content">
	    <div class="kdm-tag">Service 01</div>
	    <h2>Google Business<br><em>Profile</em><br>Optimization</h2>
	    <p>Your Google Business Profile is often the first thing a customer sees — and most businesses leave it half-finished. We fully optimize your GBP so it converts searchers into calls, visits, and leads.</p>
	    <p>From categories and service areas to review strategy and Q&A, we handle every element that signals trust and relevance to Google\'s local algorithm.</p>
	    <a href="#quote" class="kdm-cta-link">Optimize My GBP &rarr;</a>
	  </div>
	  <div>
	    <div class="kdm-mockup-card">
	      <div class="kdm-mockup-header">
	        <div class="kdm-dot kdm-dot-r"></div><div class="kdm-dot kdm-dot-y"></div><div class="kdm-dot kdm-dot-g"></div>
	        <span class="kdm-mockup-title">GBP Performance Dashboard</span>
	      </div>
	      <div class="kdm-mockup-body">
	        <div class="kdm-gbp-score">
	          <div class="kdm-score-ring"><span>94</span></div>
	          <div class="kdm-score-info">
	            <h4 style="margin: 0">Profile Strength</h4>
	            <p>Fully optimized &amp; verified</p>
	          </div>
	        </div>
	        <div class="kdm-gbp-metrics">
	          <div class="kdm-gbp-metric">
	            <div class="kdm-num">3.2K</div>
	            <div class="kdm-lbl">Monthly Views</div>
	          </div>
	          <div class="kdm-gbp-metric">
	            <div class="kdm-num">218</div>
	            <div class="kdm-lbl">Direction Requests</div>
	          </div>
	          <div class="kdm-gbp-metric">
	            <div class="kdm-num">4.9★</div>
	            <div class="kdm-lbl">Avg Rating</div>
	          </div>
	        </div>
	      </div>
	    </div>
	  </div>
	</section>
	<!-- /wp:html -->
</div>
<!-- /wp:group -->'
		)
	);

	// Keep adding the other patterns: Citations, Location Pages, Maps...
	// Just for brevity in scaffolding, we add the FAQ and Quote sections as well.

	register_block_pattern(
		'kdm-landing-blocks/areas',
		array(
			'title' => __('KDM Areas Section', 'kdm-landing-blocks'),
			'description' => _x('Areas grid.', 'Block pattern description', 'kdm-landing-blocks'),
			'categories' => array('kdm-landing'),
			'content' => '
<!-- wp:group {"align":"full","className":"kdm-landing-pattern-wrapper","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull kdm-landing-pattern-wrapper">
	<!-- wp:html -->
	<section class="kdm-areas-section">
	  <div class="kdm-section-label">Areas We Serve</div>
	  <h2>Local SEO for <em>Downtown Toronto</em><br>and <em>East York</em></h2>
	  <p class="kdm-sub">We don\'t just understand Toronto — we know how customers in each neighbourhood search, what they expect, and how to make your business the obvious choice.</p>
	  <div class="kdm-areas-grid">
	    <div class="kdm-area-card">
	      <h3>Downtown Toronto</h3>
	      <p>The most competitive local market in Canada. We help service businesses, restaurants, retail, and professionals cut through the noise and rank for the high-intent searches driving real foot traffic and phone calls in the core.</p>
	    </div>
	    <div class="kdm-area-card">
	      <h3>East York</h3>
	      <p>A growing community with strong neighbourhood loyalty. Customers here search hyper-locally — by street, by intersection. We build location pages and GBP strategies that tap into exactly how East York searches.</p>
	    </div>
	  </div>
	</section>
	<!-- /wp:html -->
</div>
<!-- /wp:group -->'
		)
	);

	register_block_pattern(
		'kdm-landing-blocks/faq',
		array(
			'title' => __('KDM FAQ', 'kdm-landing-blocks'),
			'description' => _x('FAQ block using HTML details tag.', 'Block pattern description', 'kdm-landing-blocks'),
			'categories' => array('kdm-landing'),
			'content' => '
<!-- wp:group {"align":"full","className":"kdm-landing-pattern-wrapper","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull kdm-landing-pattern-wrapper">
	<!-- wp:html -->
	<section class="kdm-faq-section">
	  <h2>Local SEO <em>FAQs</em></h2>
	
	  <div class="kdm-faq-item">
	    <details>
	      <summary>How long does it take to see results from local SEO in Toronto?</summary>
	      <p>Most Toronto businesses start seeing measurable movement in Google Maps and local search rankings within 60–90 days. Competitive industries like legal, dental, or home services may take 4–6 months to reach the Local Pack consistently. We focus on quick wins first — GBP optimization and citation cleanup tend to show results the fastest.</p>
	    </details>
	  </div>
	
	  <div class="kdm-faq-item">
	    <details>
	      <summary>Do I need a physical address in Toronto to rank locally?</summary>
	      <p>Not necessarily. Service-area businesses (plumbers, cleaners, mobile services) can rank in Google Maps without a storefront. We set up your GBP as a service-area business and build the right local signals so you appear for searches in your target neighbourhoods, even without a traditional address.</p>
	    </details>
	  </div>
	</section>
	<!-- /wp:html -->
</div>
<!-- /wp:group -->'
		)
	);

	register_block_pattern(
		'kdm-landing-blocks/quote',
		array(
			'title' => __('KDM Quote Form Section', 'kdm-landing-blocks'),
			'description' => _x('The bottom quote CTA block.', 'Block pattern description', 'kdm-landing-blocks'),
			'categories' => array('kdm-landing'),
			'content' => '
<!-- wp:group {"align":"full","className":"kdm-landing-pattern-wrapper","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull kdm-landing-pattern-wrapper">
	<!-- wp:html -->
	<section class="kdm-quote-section" id="quote">
	  <h2>Request A Quote</h2>
	  <form class="kdm-quote-form">
	    <div class="kdm-form-group">
	      <label>First Name</label>
	      <input type="text" placeholder="Jane" />
	    </div>
	    <div class="kdm-form-group">
	      <label>Last Name</label>
	      <input type="text" placeholder="Smith" />
	    </div>
	    <div class="kdm-form-group">
	      <label>Email</label>
	      <input type="email" placeholder="jane@company.com" />
	    </div>
	    <div class="kdm-form-group">
	      <label>Phone</label>
	      <input type="tel" placeholder="416-555-0100" />
	    </div>
	    <div class="kdm-form-group" style="grid-column:1/-1">
	      <label>Message</label>
	      <textarea placeholder="Tell us about your business and goals…"></textarea>
	    </div>
	    <button type="submit" class="kdm-quote-submit">Start Your Free, No-Obligation Consultation →</button>
	  </form>
	</section>
	<!-- /wp:html -->
</div>
<!-- /wp:group -->'
		)
	);
}
add_action('init', 'kdm_lb_register_patterns');
