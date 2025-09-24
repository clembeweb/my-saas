<?php

namespace App\Services;

class GoogleAdsScriptGenerator
{
    public function generateCampaignSyncScript(string $syncToken, string $apiEndpoint): string
    {
        return <<<JS
/**
 * SaaS Platform - Google Ads Campaign Sync Script
 * Automatically synchronizes campaign data with your SaaS platform
 *
 * Instructions:
 * 1. Copy this entire script
 * 2. Go to Google Ads > Tools & Settings > Scripts
 * 3. Click the "+" button to create a new script
 * 4. Paste this code and save
 * 5. Click "Run" to execute immediately or schedule it
 */

function main() {
  // Configuration
  const SYNC_TOKEN = '{$syncToken}';
  const API_ENDPOINT = '{$apiEndpoint}';
  const DATE_RANGE = 'LAST_30_DAYS'; // Options: TODAY, YESTERDAY, LAST_7_DAYS, LAST_30_DAYS, THIS_MONTH, LAST_MONTH

  try {
    console.log('Starting Google Ads data sync...');

    // Get account information
    const account = AdsApp.currentAccount();
    const accountInfo = {
      account_id: account.getCustomerId(),
      account_name: account.getName(),
      currency_code: account.getCurrencyCode(),
      time_zone: account.getTimeZone()
    };

    console.log('Account Info:', accountInfo);

    // Get campaigns data
    const campaigns = getCampaignsData(DATE_RANGE);
    console.log('Found ' + campaigns.length + ' campaigns');

    // Prepare data payload
    const payload = {
      sync_token: SYNC_TOKEN,
      account_id: accountInfo.account_id,
      account_name: accountInfo.account_name,
      currency_code: accountInfo.currency_code,
      time_zone: accountInfo.time_zone,
      campaigns: campaigns
    };

    // Send data to platform
    const response = sendDataToPlatform(API_ENDPOINT, payload);

    if (response && response.success) {
      console.log('✅ Sync completed successfully!');
      console.log('Campaigns synchronized: ' + response.data.campaigns_count);
    } else {
      console.error('❌ Sync failed:', response ? response.message : 'Unknown error');
    }

  } catch (error) {
    console.error('❌ Script error:', error.toString());
  }
}

function getCampaignsData(dateRange) {
  const campaigns = [];

  const campaignIterator = AdsApp.campaigns()
    .forDateRange(dateRange)
    .withCondition('Status IN [ENABLED, PAUSED]')
    .get();

  while (campaignIterator.hasNext()) {
    const campaign = campaignIterator.next();
    const stats = campaign.getStatsFor(dateRange);

    const campaignData = {
      id: campaign.getId().toString(),
      name: campaign.getName(),
      status: campaign.isEnabled() ? 'ENABLED' : 'PAUSED',
      type: getCampaignType(campaign),
      impressions: stats.getImpressions(),
      clicks: stats.getClicks(),
      cost: stats.getCost(),
      conversions: stats.getConversions(),
      ctr: stats.getCtr(),
      avg_cpc: stats.getAverageCpc()
    };

    campaigns.push(campaignData);

    // Log campaign info
    console.log('Campaign: ' + campaignData.name + ' | Impressions: ' + campaignData.impressions + ' | Clicks: ' + campaignData.clicks);
  }

  return campaigns;
}

function getCampaignType(campaign) {
  // Get campaign type/subtype
  try {
    if (campaign.getType) {
      return campaign.getType();
    }

    // Fallback - try to determine from campaign settings
    const name = campaign.getName().toLowerCase();
    if (name.includes('shopping')) return 'SHOPPING';
    if (name.includes('display')) return 'DISPLAY';
    if (name.includes('video') || name.includes('youtube')) return 'VIDEO';
    if (name.includes('search')) return 'SEARCH';
    if (name.includes('performance') || name.includes('pmax')) return 'PERFORMANCE_MAX';

    return 'SEARCH'; // Default fallback
  } catch (e) {
    return 'SEARCH';
  }
}

function sendDataToPlatform(endpoint, data) {
  try {
    const options = {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'User-Agent': 'GoogleAdsScript/1.0'
      },
      payload: JSON.stringify(data)
    };

    console.log('Sending data to: ' + endpoint);
    console.log('Payload size: ' + JSON.stringify(data).length + ' characters');

    const response = UrlFetchApp.fetch(endpoint, options);
    const responseCode = response.getResponseCode();
    const responseText = response.getContentText();

    console.log('Response Code: ' + responseCode);
    console.log('Response: ' + responseText);

    if (responseCode === 200) {
      return JSON.parse(responseText);
    } else {
      console.error('HTTP Error ' + responseCode + ': ' + responseText);
      return { success: false, message: 'HTTP ' + responseCode + ': ' + responseText };
    }

  } catch (error) {
    console.error('Network error:', error.toString());
    return { success: false, message: 'Network error: ' + error.toString() };
  }
}

// Test function - uncomment to test API connectivity
/*
function testConnection() {
  const testPayload = {
    sync_token: '{$syncToken}',
    test: true
  };

  console.log('Testing connection...');
  const response = sendDataToPlatform('{$apiEndpoint}', testPayload);
  console.log('Test result:', response);
}
*/
JS;
    }

    public function generateKeywordSyncScript(string $syncToken, string $apiEndpoint): string
    {
        return <<<JS
/**
 * SaaS Platform - Google Ads Keywords Sync Script
 * Synchronizes keyword performance data with your SaaS platform
 */

function main() {
  const SYNC_TOKEN = '{$syncToken}';
  const API_ENDPOINT = '{$apiEndpoint}';
  const DATE_RANGE = 'LAST_30_DAYS';

  try {
    console.log('Starting keywords sync...');

    const keywords = getKeywordsData(DATE_RANGE);
    console.log('Found ' + keywords.length + ' keywords');

    const payload = {
      sync_token: SYNC_TOKEN,
      keywords: keywords
    };

    const response = sendDataToPlatform(API_ENDPOINT, payload);

    if (response && response.success) {
      console.log('✅ Keywords sync completed!');
    } else {
      console.error('❌ Keywords sync failed:', response ? response.message : 'Unknown error');
    }

  } catch (error) {
    console.error('❌ Keywords script error:', error.toString());
  }
}

function getKeywordsData(dateRange) {
  const keywords = [];

  const keywordIterator = AdsApp.keywords()
    .forDateRange(dateRange)
    .withCondition('Status IN [ENABLED, PAUSED]')
    .get();

  while (keywordIterator.hasNext()) {
    const keyword = keywordIterator.next();
    const stats = keyword.getStatsFor(dateRange);

    const keywordData = {
      campaign_id: keyword.getCampaign().getId().toString(),
      ad_group_id: keyword.getAdGroup().getId().toString(),
      keyword_id: keyword.getId().toString(),
      keyword_text: keyword.getText(),
      match_type: keyword.getMatchType(),
      status: keyword.isEnabled() ? 'ENABLED' : 'PAUSED',
      impressions: stats.getImpressions(),
      clicks: stats.getClicks(),
      cost: stats.getCost(),
      conversions: stats.getConversions()
    };

    keywords.push(keywordData);
  }

  return keywords;
}

function sendDataToPlatform(endpoint, data) {
  try {
    const options = {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json'
      },
      payload: JSON.stringify(data)
    };

    const response = UrlFetchApp.fetch(endpoint, options);
    const responseCode = response.getResponseCode();
    const responseText = response.getContentText();

    if (responseCode === 200) {
      return JSON.parse(responseText);
    } else {
      console.error('HTTP Error ' + responseCode + ': ' + responseText);
      return { success: false, message: 'HTTP ' + responseCode };
    }

  } catch (error) {
    console.error('Network error:', error.toString());
    return { success: false, message: error.toString() };
  }
}
JS;
    }
}
