/**
 * AI Chat Enhanced Responses
 * Specialized responses for different AI assistants
 */

const AI_RESPONSES = {
    // Sermon Builder
    sermon: {
        keywords: ['sermon', 'preach', 'message', 'outline'],
        generate: (topic) => {
            return `📖 **Sermon Outline**

**Title:** "${topic || 'Faith That Moves Mountains'}"

**Scripture References:**
• Primary: Matthew 17:20
• Supporting: Hebrews 11:1, James 2:14-26

**Introduction (5 minutes)**
• Hook: Share a relatable story about facing impossible situations
• Context: Explain the biblical setting
• Transition: "Today we'll discover the power of genuine faith"

**Main Points (20 minutes)**

**I. Understanding True Faith**
   A. Biblical definition (Hebrews 11:1)
   B. Not just belief, but trust and action
   C. Examples from Scripture

**II. Faith in Action**
   A. Abraham's journey (Genesis 12)
   B. Modern-day applications
   C. Overcoming doubt with faith

**III. Growing Your Faith**
   A. Through prayer and fasting
   B. Through God's Word
   C. Through community and fellowship

**Illustrations:**
• Story of George Müller and answered prayer
• Personal testimony of faith in action
• Current event connection

**Conclusion (5 minutes)**
• Recap the three main points
• Challenge: Take one faith step this week
• Closing prayer

**Discussion Questions:**
1. What area of your life needs more faith?
2. How have you seen God's faithfulness?
3. What's your next faith step?`;
        }
    },

    // Bible Study Generator
    'bible-study': {
        keywords: ['bible study', 'study guide', 'lesson'],
        generate: (topic) => {
            return `📚 **Bible Study Guide**

**Topic:** ${topic || 'The Power of Faith'}

**Objective:** 
Help participants understand and apply faith in their daily lives.

**Opening Prayer (2 min)**
"Lord, open our hearts to Your Word. Teach us about faith today."

**Ice Breaker (5 min)**
Share one area where you're trusting God right now.

**Scripture Reading (10 min)**
• Hebrews 11:1-6
• Romans 10:17
• James 2:14-26

**Discussion Questions:**
1. **Understanding Faith**
   - What does Hebrews 11:1 teach us about faith?
   - How is faith different from hope or wishful thinking?

2. **Faith and Works**
   - Why does James say faith without works is dead?
   - Can you give an example of faith in action?

3. **Growing in Faith**
   - How does faith come according to Romans 10:17?
   - What practices help strengthen your faith?

4. **Practical Application**
   - What obstacle are you facing that requires faith?
   - How will you exercise faith this week?

**Group Activity (15 min)**
Pair up and pray for each other's faith challenges.

**Key Takeaways:**
✅ Faith is trusting God even when we can't see
✅ True faith leads to action
✅ Faith grows through God's Word and prayer

**Memory Verse:**
"Now faith is confidence in what we hope for and assurance about what we do not see." - Hebrews 11:1

**Homework:**
Journal about one way you saw God's faithfulness this week.`;
        }
    },

    // SMS Writer
    sms: {
        keywords: ['sms', 'text', 'message', 'reminder'],
        generate: (purpose) => {
            return `📱 **SMS Message Templates**

**Option 1 - Standard Reminder:**
"Hi [Name], reminder: ${purpose || 'Prayer Meeting'} tonight at 7 PM at the church. Your presence matters! - [Church Name]"
📊 Characters: 95/160

**Option 2 - Urgent:**
"🔔 TONIGHT: ${purpose || 'Special Service'} at 7 PM! Don't miss this blessing. See you there! - [Church Name]"
📊 Characters: 87/160

**Option 3 - Warm Invitation:**
"Hello [Name]! We'd love to see you at ${purpose || 'our service'} tonight, 7 PM. Bring a friend! God bless - [Church Name]"
📊 Characters: 102/160

**Option 4 - With Scripture:**
"'Come, let us go to the house of the Lord' (Ps 122:1). ${purpose || 'Service'} tonight 7PM. Join us! - [Church Name]"
📊 Characters: 98/160

**Best Practices:**
✅ Keep under 160 characters
✅ Include time and location
✅ Add call to action
✅ Use personalization [Name]
✅ Add church name/signature

**Send using:**
• Your SMS gateway
• Bulk SMS service
• Church management system

Ready to send? Just copy and personalize!`;
        }
    },

    // Prayer Writer
    prayer: {
        keywords: ['prayer', 'pray', 'blessing'],
        generate: (occasion) => {
            const prayers = {
                opening: `🙏 **Opening Prayer**

Heavenly Father,

We gather in Your presence with grateful hearts. Thank You for bringing us together in worship and fellowship.

As we open Your Word today, speak to our hearts. Transform us by Your Spirit. Give us ears to hear and hearts to receive Your truth.

Bless this time together. May every word spoken glorify Your name, and may we leave here refreshed and renewed in our faith.

In Jesus' precious name we pray,
Amen.`,

                closing: `🙏 **Closing Prayer**

Lord God,

Thank You for Your Word that has been shared today. Help us to be doers of the Word, not hearers only.

As we go from this place, may Your presence go with us. Protect us, guide us, and use us to be lights in our communities.

Watch over each person here. Meet their needs, heal their hurts, and strengthen their faith.

Until we meet again, keep us in Your perfect peace.

In Jesus' name,
Amen.`,

                blessing: `🙏 **Benediction**

May the Lord bless you and keep you;
May His face shine upon you and be gracious to you;
May He turn His face toward you and give you peace.

Go in the power of the Holy Spirit,
Live as children of light,
And may God's favor rest upon you this week.

In the name of the Father, Son, and Holy Spirit,
Amen.`
            };

            return prayers[occasion] || prayers.opening;
        }
    },

    // Financial Analyst
    finance: {
        keywords: ['finance', 'money', 'giving', 'tithe', 'offering', 'budget'],
        generate: (query) => {
            return `💰 **Financial Analysis Report**

**Monthly Giving Summary**

**Total Income: GH₵45,250**
├─ Tithes: GH₵32,000 (71%)
├─ Offerings: GH₵10,250 (23%)
├─ Special Gifts: GH₵2,500 (5%)
└─ Other: GH₵500 (1%)

**Total Expenses: GH₵32,100**
├─ Salaries: GH₵18,000 (56%)
├─ Rent & Utilities: GH₵8,000 (25%)
├─ Ministry Programs: GH₵4,100 (13%)
└─ Administrative: GH₵2,000 (6%)

**Net Surplus: +GH₵13,150**

**Key Insights:**
📈 Income up 12% from last month
📉 Expenses remained stable
✅ Healthy surplus maintained
⚠️ Watch ministry program costs

**Giving Trends:**
• Average per member: GH₵285
• Attendance: 112 (avg)
• Online giving: 28% of total
• Recurring givers: 45 families

**Recommendations:**
1. ✅ Allocate GH₵10,000 to building fund
2. 📊 Increase ministry budget by 5%
3. 🎯 Launch stewardship campaign
4. 💳 Promote mobile giving options

**Budget Health Score: 92/100**
Status: 🟢 Excellent

**Next Steps:**
• Review quarterly projections
• Update donor thank-you letters
• Plan year-end giving campaign

Need detailed breakdown? Ask about specific categories!`;
        }
    },

    // Meeting Minutes
    minutes: {
        keywords: ['meeting', 'minutes', 'notes', 'agenda'],
        generate: (meetingType) => {
            return `📋 **Meeting Minutes Template**

**${meetingType || 'Church Leadership'} Meeting**
**Date:** ${new Date().toLocaleDateString()}
**Time:** [Start] - [End]
**Location:** [Venue/Online]

**Attendees:**
✅ [Name 1] - [Role]
✅ [Name 2] - [Role]
✅ [Name 3] - [Role]
❌ [Name 4] - [Role] (Absent)

**Agenda:**
1. Opening Prayer
2. Review of Previous Minutes
3. Financial Report
4. Ministry Updates
5. New Business
6. Closing

---

**DISCUSSION POINTS**

**1. Financial Report** (Presenter: [Name])
• Income this month: GH₵45,250
• Expenses: GH₵32,100
• Net: +GH₵13,150
• **Decision:** Approved unanimously

**2. Upcoming Events**
• Revival Meeting: Nov 15-17
• Youth Conference: Nov 25
• Christmas Program: Dec 24
• **Decision:** Approved event budget

**3. New Ministry Initiative**
• Proposal: Community Outreach Program
• Budget needed: GH₵5,000
• Timeline: Start January
• **Decision:** Approved pending budget review

---

**ACTION ITEMS**

| Task | Assigned To | Deadline | Status |
|------|-------------|----------|--------|
| Finalize revival speakers | Pastor John | Oct 25 | ⏳ Pending |
| Order Christmas decorations | Admin | Nov 1 | ⏳ Pending |
| Review insurance policy | Treasurer | Nov 15 | ⏳ Pending |

---

**DECISIONS MADE:**
1. ✅ Approved monthly financial report
2. ✅ Approved event calendar for Q4
3. ✅ Authorized GH₵5,000 for outreach
4. ⏳ Deferred discussion on building renovation

**NEXT MEETING:**
📅 [Date]
🕐 [Time]
📍 [Location]

**Closing Prayer:** [Name]

**Minutes Prepared By:** [Secretary Name]
**Date:** ${new Date().toLocaleDateString()}

---

*These minutes are subject to approval at the next meeting.*`;
        }
    },

    // Letter Generator
    letter: {
        keywords: ['letter', 'correspondence', 'official'],
        generate: (purpose) => {
            return `✉️ **Official Church Letter**

[Church Letterhead]

**[Church Name]**
[Address]
[City, State, ZIP]
[Phone] | [Email] | [Website]

---

**Date:** ${new Date().toLocaleDateString()}

**To:** [Recipient Name]
**Address:** [Recipient Address]

**Subject:** ${purpose || 'Church Correspondence'}

Dear [Recipient Name],

**[Opening Paragraph]**
Grace and peace to you in the name of our Lord Jesus Christ. We trust this letter finds you and your family in good health and high spirits.

**[Body Paragraph 1]**
We are writing to [state purpose]. As a valued member of our church community, we wanted to personally reach out to you regarding [specific matter].

**[Body Paragraph 2]**
[Provide details, explain situation, or make request]. We believe that [explain reasoning or benefits]. Your support and participation would be greatly appreciated.

**[Closing Paragraph]**
Thank you for your continued faithfulness and dedication to our ministry. Should you have any questions or need further information, please do not hesitate to contact us.

May God's blessings be upon you and your family.

In Christian love,

**[Pastor/Leader Name]**
[Title]
[Church Name]

---

**Enclosures:** [If any]
**CC:** [If any]

---

**Types of Letters I can help with:**
• Membership confirmation
• Event invitations
• Recommendation letters
• Thank you letters
• Official announcements
• Reference letters

Just let me know what you need!`;
        }
    },

    // Report Bot
    report: {
        keywords: ['report', 'summary', 'statistics'],
        generate: (reportType) => {
            return `📊 **${reportType || 'Monthly Ministry'} Report**

**Period:** ${new Date().toLocaleDateString('en-US', {month: 'long', year: 'numeric'})}
**Generated:** ${new Date().toLocaleString()}

---

**EXECUTIVE SUMMARY**

✅ Total Attendance: 2,450 (avg 612/week)
✅ New Members: 12
✅ Baptisms: 8
✅ Total Giving: GH₵45,250
✅ Ministry Events: 15

**Performance vs Goals:**
• Attendance: 102% of target ✅
• Giving: 98% of budget ⚠️
• New Members: 120% of target ✅

---

**ATTENDANCE BREAKDOWN**

**Sunday Services:**
• Morning Service: 385 avg
• Evening Service: 145 avg
• Total: 530 avg

**Weekday Services:**
• Tuesday Prayer: 45 avg
• Thursday Bible Study: 37 avg

**Age Demographics:**
• Children (0-12): 28%
• Youth (13-19): 18%
• Adults (20-64): 45%
• Seniors (65+): 9%

---

**FINANCIAL HIGHLIGHTS**

**Income Sources:**
💰 Tithes: GH₵32,000
💰 Offerings: GH₵10,250
💰 Special Gifts: GH₵3,000

**Major Expenses:**
💸 Personnel: GH₵18,000
💸 Facilities: GH₵8,000
💸 Ministries: GH₵4,100
💸 Admin: GH₵2,000

**Surplus:** +GH₵13,150

---

**MINISTRY ACTIVITIES**

**Outreach:**
• Community events: 3
• Home visits: 24
• Hospital visits: 8

**Growth:**
• New visitors: 42
• Follow-up contacts: 38
• Conversions: 6

**Pastoral Care:**
• Counseling sessions: 15
• Prayer requests: 67
• Sick visits: 12

---

**UPCOMING INITIATIVES**

🎯 Revival Meeting (Nov 15-17)
🎯 Youth Conference (Nov 25)
🎯 Christmas Program (Dec 24)
🎯 Year-end Giving Campaign

---

**RECOMMENDATIONS**

1. ✅ Continue current growth trajectory
2. 📈 Increase youth engagement by 15%
3. 💰 Launch stewardship campaign
4. 🤝 Expand community outreach
5. 📱 Enhance digital presence

---

**CONCLUSION**

The ministry shows healthy growth across all metrics. With continued effort and God's blessing, we're positioned for an excellent year-end.

**Prepared by:** [Name]
**Approved by:** [Pastor/Leader]

---

Need a different report? I can generate:
• Financial reports
• Attendance analysis
• Ministry performance
• Quarterly summaries
• Annual reviews`;
        }
    }
};

// Get AI response based on current assistant role
function getEnhancedAIResponse(message, currentRole = 'general') {
    const msg = message.toLowerCase();
    
    // Check if we have a specialized response
    if (AI_RESPONSES[currentRole]) {
        const assistant = AI_RESPONSES[currentRole];
        
        // Check if message matches assistant keywords
        const matchesKeyword = assistant.keywords.some(keyword => 
            msg.includes(keyword)
        );
        
        if (matchesKeyword || currentRole !== 'general') {
            return assistant.generate(message);
        }
    }
    
    // Fallback to general response
    return getGeneralResponse(msg);
}

// General AI responses
function getGeneralResponse(msg) {
    if (msg.includes('hello') || msg.includes('hi')) {
        return `👋 **Welcome to Church AI Assistant!**

I'm here to help with:

📖 **Ministry & Teaching**
• Sermon outlines & Bible studies
• Prayer writing & devotionals
• Youth & children's programs

💼 **Administration**
• Meeting minutes & reports
• Official letters & announcements
• Event planning & coordination

💰 **Financial Management**
• Giving analysis & reports
• Budget planning & forecasting
• Financial recommendations

📱 **Communication**
• SMS & email templates
• Social media content
• Newsletters & bulletins

Just select an AI assistant from the sidebar or tell me what you need!`;
    }
    
    return `I'm here to help! I can assist with:

✅ Sermon preparation & outlines
✅ Bible study guides
✅ Prayer writing
✅ SMS & email drafts
✅ Financial analysis
✅ Meeting minutes
✅ Official letters
✅ Ministry reports

**What would you like me to help with?**`;
}

// Export functions
window.getEnhancedAIResponse = getEnhancedAIResponse;
window.AI_RESPONSES = AI_RESPONSES;

console.log('✅ AI Chat Enhanced Responses module loaded');
