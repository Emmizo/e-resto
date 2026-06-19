"""
Generate ERD image for e-resto Laravel database schema.
Run: python3 generate_erd.py
Output: erd.png in the same directory
"""

import matplotlib
matplotlib.use('Agg')
import matplotlib.pyplot as plt
import matplotlib.patches as mpatches
from matplotlib.patches import FancyBboxPatch
import matplotlib.patheffects as pe

# ─────────────────────────────────────────────────────────────────
# Schema definition: (table_name, [(col_name, col_type, is_pk, is_fk)])
# ─────────────────────────────────────────────────────────────────
TABLES = {
    "users": [
        ("id", "bigint PK", True, False),
        ("first_name", "varchar", False, False),
        ("last_name", "varchar", False, False),
        ("email", "varchar UQ", False, False),
        ("role", "enum", False, False),
        ("phone_number", "varchar", False, False),
        ("status", "int", False, False),
        ("fcm_token", "varchar", False, False),
        ("timezone", "varchar", False, False),
        ("created_at / updated_at", "timestamp", False, False),
    ],
    "cuisines": [
        ("id", "bigint PK", True, False),
        ("name", "varchar UQ", False, False),
        ("created_at / updated_at", "timestamp", False, False),
    ],
    "restaurants": [
        ("id", "bigint PK", True, False),
        ("owner_id", "bigint FK→users", False, True),
        ("cuisine_id", "bigint FK→cuisines", False, True),
        ("name", "varchar", False, False),
        ("address", "varchar", False, False),
        ("latitude / longitude", "decimal", False, False),
        ("opening_hours", "json", False, False),
        ("is_approved", "boolean", False, False),
        ("accepts_reservations", "boolean", False, False),
        ("accepts_delivery", "boolean", False, False),
        ("created_at / updated_at", "timestamp", False, False),
    ],
    "menus": [
        ("id", "bigint PK", True, False),
        ("restaurant_id", "bigint FK→restaurants", False, True),
        ("name", "varchar", False, False),
        ("is_active", "boolean", False, False),
        ("created_at / updated_at", "timestamp", False, False),
    ],
    "menu_items": [
        ("id", "bigint PK", True, False),
        ("menu_id", "bigint FK→menus", False, True),
        ("name", "varchar", False, False),
        ("price", "decimal(8,2)", False, False),
        ("category", "varchar", False, False),
        ("is_available", "boolean", False, False),
        ("stock_quantity", "int?", False, False),
        ("track_inventory", "boolean", False, False),
        ("total_sold", "int", False, False),
        ("created_at / updated_at", "timestamp", False, False),
    ],
    "orders": [
        ("id", "bigint PK", True, False),
        ("user_id", "bigint FK→users", False, True),
        ("restaurant_id", "bigint FK→restaurants", False, True),
        ("table_id", "bigint? FK→tables", False, True),
        ("order_type", "enum", False, False),
        ("total_amount", "decimal(10,2)", False, False),
        ("status", "enum", False, False),
        ("payment_status", "enum", False, False),
        ("scheduled_time", "datetime?", False, False),
        ("delivery_address", "text?", False, False),
        ("created_at / updated_at", "timestamp", False, False),
    ],
    "order_items": [
        ("id", "bigint PK", True, False),
        ("order_id", "bigint FK→orders", False, True),
        ("menu_item_id", "bigint FK→menu_items", False, True),
        ("quantity", "int", False, False),
        ("price", "decimal(8,2)", False, False),
        ("created_at / updated_at", "timestamp", False, False),
    ],
    "reservations": [
        ("id", "bigint PK", True, False),
        ("user_id", "bigint FK→users", False, True),
        ("restaurant_id", "bigint FK→restaurants", False, True),
        ("reservation_time", "datetime", False, False),
        ("number_of_people", "int", False, False),
        ("status", "enum", False, False),
        ("special_requests", "text?", False, False),
        ("phone_number", "varchar?", False, False),
        ("created_at / updated_at", "timestamp", False, False),
    ],
    "tables": [
        ("id", "bigint PK", True, False),
        ("restaurant_id", "bigint FK→restaurants", False, True),
        ("table_number", "varchar", False, False),
        ("status", "varchar", False, False),
        ("created_at / updated_at", "timestamp", False, False),
    ],
    "reservation_table": [
        ("id", "bigint PK", True, False),
        ("reservation_id", "bigint FK→reservations", False, True),
        ("table_id", "bigint FK→tables", False, True),
        ("created_at / updated_at", "timestamp", False, False),
    ],
    "reviews": [
        ("id", "bigint PK", True, False),
        ("user_id", "bigint FK→users", False, True),
        ("restaurant_id", "bigint FK→restaurants", False, True),
        ("rating", "int", False, False),
        ("comment", "text?", False, False),
        ("created_at / updated_at", "timestamp", False, False),
    ],
    "promo_banners": [
        ("id", "bigint PK", True, False),
        ("restaurant_id", "bigint FK→restaurants", False, True),
        ("title", "varchar", False, False),
        ("start_date / end_date", "date?", False, False),
        ("is_active", "boolean", False, False),
        ("created_at / updated_at", "timestamp", False, False),
    ],
    "restaurant_employees": [
        ("id", "bigint PK", True, False),
        ("user_id", "bigint FK→users", False, True),
        ("restaurant_id", "bigint FK→restaurants", False, True),
        ("position", "enum", False, False),
        ("is_active", "boolean", False, False),
        ("created_at / updated_at", "timestamp", False, False),
    ],
    "restaurant_tags": [
        ("id", "bigint PK", True, False),
        ("name", "varchar UQ", False, False),
        ("created_at / updated_at", "timestamp", False, False),
    ],
    "restaurant_tag_relations": [
        ("id", "bigint PK", True, False),
        ("restaurant_id", "bigint FK→restaurants", False, True),
        ("tag_id", "bigint FK→restaurant_tags", False, True),
        ("created_at / updated_at", "timestamp", False, False),
    ],
    "restaurant_permissions": [
        ("id", "bigint PK", True, False),
        ("user_id", "bigint FK→users", False, True),
        ("restaurant_id", "bigint? FK→restaurants", False, True),
        ("permission_name", "varchar", False, False),
        ("is_active", "boolean", False, False),
        ("level", "varchar?", False, False),
        ("created_at / updated_at", "timestamp", False, False),
    ],
    "notifications": [
        ("id", "bigint PK", True, False),
        ("user_id", "bigint FK→users", False, True),
        ("restaurant_id", "bigint?", False, False),
        ("title", "varchar", False, False),
        ("body", "text", False, False),
        ("is_read", "boolean", False, False),
        ("created_at / updated_at", "timestamp", False, False),
    ],
    "user_preferences": [
        ("id", "bigint PK", True, False),
        ("user_id", "bigint FK→users", False, True),
        ("cuisine_preference", "varchar?", False, False),
        ("price_range_preference", "varchar?", False, False),
        ("dietary_restrictions", "json?", False, False),
        ("created_at / updated_at", "timestamp", False, False),
    ],
    "favorite_restaurants": [
        ("id", "bigint PK", True, False),
        ("user_id", "bigint FK→users", False, True),
        ("restaurant_id", "bigint FK→restaurants", False, True),
        ("created_at / updated_at", "timestamp", False, False),
    ],
    "favorite_menu_items": [
        ("id", "bigint PK", True, False),
        ("user_id", "bigint FK→users", False, True),
        ("menu_item_id", "bigint FK→menu_items", False, True),
        ("status", "boolean", False, False),
        ("created_at / updated_at", "timestamp", False, False),
    ],
    "addresses": [
        ("id", "bigint PK", True, False),
        ("user_id", "bigint FK→users", False, True),
        ("title", "varchar", False, False),
        ("address", "text", False, False),
        ("type", "varchar", False, False),
        ("is_default", "boolean", False, False),
        ("created_at / updated_at", "timestamp", False, False),
    ],
}

# ─────────────────────────────────────────────────────────────────
# Layout: (table_name, (x_centre, y_centre))
# Canvas is in "table units"; each table box is drawn relative to centre
# ─────────────────────────────────────────────────────────────────
LAYOUT = {
    # Core
    "users":                  (11, 52),
    "cuisines":               (11, 21),
    # Restaurant cluster
    "restaurants":            (32, 37),
    "menus":                  (54, 52),
    "menu_items":             (76, 52),
    "promo_banners":          (54, 21),
    "tables":                 (54, 37),
    # Order cluster
    "orders":                 (98, 37),
    "order_items":            (98, 52),
    # Reservation cluster
    "reservations":           (76, 21),
    "reservation_table":      (76, 6),
    # User relations
    "reviews":                (32, 6),
    "restaurant_employees":   (32, 52),
    "restaurant_tags":        (11, 6),
    "restaurant_tag_relations": (11, 37),
    "restaurant_permissions": (54, 6),
    "notifications":          (98, 6),
    "user_preferences":       (98, 21),
    "favorite_restaurants":   (120, 37),
    "favorite_menu_items":    (120, 52),
    "addresses":              (120, 21),
}

# ─────────────────────────────────────────────────────────────────
# Relationships: (from_table, to_table, label)
# ─────────────────────────────────────────────────────────────────
RELATIONSHIPS = [
    ("restaurants", "users", "owner_id"),
    ("restaurants", "cuisines", "cuisine_id"),
    ("menus", "restaurants", "restaurant_id"),
    ("menu_items", "menus", "menu_id"),
    ("promo_banners", "restaurants", "restaurant_id"),
    ("tables", "restaurants", "restaurant_id"),
    ("orders", "users", "user_id"),
    ("orders", "restaurants", "restaurant_id"),
    ("orders", "tables", "table_id"),
    ("order_items", "orders", "order_id"),
    ("order_items", "menu_items", "menu_item_id"),
    ("reservations", "users", "user_id"),
    ("reservations", "restaurants", "restaurant_id"),
    ("reservation_table", "reservations", "reservation_id"),
    ("reservation_table", "tables", "table_id"),
    ("reviews", "users", "user_id"),
    ("reviews", "restaurants", "restaurant_id"),
    ("restaurant_employees", "users", "user_id"),
    ("restaurant_employees", "restaurants", "restaurant_id"),
    ("restaurant_tag_relations", "restaurants", "restaurant_id"),
    ("restaurant_tag_relations", "restaurant_tags", "tag_id"),
    ("restaurant_permissions", "users", "user_id"),
    ("restaurant_permissions", "restaurants", "restaurant_id"),
    ("notifications", "users", "user_id"),
    ("user_preferences", "users", "user_id"),
    ("favorite_restaurants", "users", "user_id"),
    ("favorite_restaurants", "restaurants", "restaurant_id"),
    ("favorite_menu_items", "users", "user_id"),
    ("favorite_menu_items", "menu_items", "menu_item_id"),
    ("addresses", "users", "user_id"),
]

# ─────────────────────────────────────────────────────────────────
# Colour palette
# ─────────────────────────────────────────────────────────────────
COLOR_HEADER = {
    "users":                  "#1565C0",
    "cuisines":               "#6A1B9A",
    "restaurants":            "#2E7D32",
    "menus":                  "#00695C",
    "menu_items":             "#00695C",
    "promo_banners":          "#558B2F",
    "tables":                 "#00695C",
    "orders":                 "#E65100",
    "order_items":            "#E65100",
    "reservations":           "#AD1457",
    "reservation_table":      "#AD1457",
    "reviews":                "#4527A0",
    "restaurant_employees":   "#2E7D32",
    "restaurant_tags":        "#283593",
    "restaurant_tag_relations":"#283593",
    "restaurant_permissions": "#4E342E",
    "notifications":          "#0277BD",
    "user_preferences":       "#1565C0",
    "favorite_restaurants":   "#1565C0",
    "favorite_menu_items":    "#1565C0",
    "addresses":              "#1565C0",
}

ROW_BG_PK    = "#FFF9C4"
ROW_BG_FK    = "#E3F2FD"
ROW_BG_PLAIN = "#FAFAFA"

HEADER_TXT   = "white"
ROW_HEIGHT   = 0.62
HEADER_H     = 1.0
PAD_X        = 0.4
COL_WIDTH    = 9.0

# ─────────────────────────────────────────────────────────────────
# Figure setup
# ─────────────────────────────────────────────────────────────────
FIG_W, FIG_H = 140, 78
fig, ax = plt.subplots(figsize=(FIG_W / 5, FIG_H / 5))
ax.set_xlim(0, FIG_W)
ax.set_ylim(0, FIG_H)
ax.axis('off')
ax.set_facecolor("#F0F4F8")
fig.patch.set_facecolor("#E8EEF4")

# Title
ax.text(FIG_W / 2, FIG_H - 1.5, "e-Resto — Entity Relationship Diagram",
        ha='center', va='top', fontsize=22, fontweight='bold', color='#1A237E',
        fontfamily='DejaVu Sans')
ax.text(FIG_W / 2, FIG_H - 3.2,
        "PK = Primary Key   FK = Foreign Key   ? = Nullable",
        ha='center', va='top', fontsize=10, color='#546E7A')

# ─────────────────────────────────────────────────────────────────
# Helper: draw one table box, return anchor dict {col: (cx, cy_mid)}
# ─────────────────────────────────────────────────────────────────
def draw_table(ax, name, columns, cx, cy):
    n_rows = len(columns)
    box_h = HEADER_H + n_rows * ROW_HEIGHT
    left  = cx - COL_WIDTH / 2
    top   = cy + box_h / 2  # top y in data coords

    # Shadow
    shadow = FancyBboxPatch((left + 0.12, top - box_h - 0.12), COL_WIDTH, box_h,
                             boxstyle="round,pad=0.1", linewidth=0,
                             facecolor="#B0BEC5", alpha=0.4, zorder=1)
    ax.add_patch(shadow)

    # Header
    hdr_color = COLOR_HEADER.get(name, "#37474F")
    hdr = FancyBboxPatch((left, top - HEADER_H), COL_WIDTH, HEADER_H,
                          boxstyle="round,pad=0.05", linewidth=0.8,
                          edgecolor="#263238", facecolor=hdr_color, zorder=2)
    ax.add_patch(hdr)
    ax.text(cx, top - HEADER_H / 2, name,
            ha='center', va='center', fontsize=9.0, fontweight='bold',
            color=HEADER_TXT, zorder=3)

    # Row backgrounds + text
    anchors = {}
    for i, (col, dtype, is_pk, is_fk) in enumerate(columns):
        row_top = top - HEADER_H - i * ROW_HEIGHT
        bg = ROW_BG_PK if is_pk else (ROW_BG_FK if is_fk else ROW_BG_PLAIN)
        shade = i % 2  # alternating subtle stripe for plain rows
        if not is_pk and not is_fk:
            bg = "#F5F5F5" if shade else "#FAFAFA"

        rect = plt.Rectangle((left, row_top - ROW_HEIGHT), COL_WIDTH, ROW_HEIGHT,
                               linewidth=0.4, edgecolor="#CFD8DC", facecolor=bg, zorder=2)
        ax.add_patch(rect)

        # PK / FK badge
        badge = ""
        badge_color = "black"
        if is_pk:
            badge = "PK"
            badge_color = "#C62828"
        elif is_fk:
            badge = "FK"
            badge_color = "#1565C0"

        if badge:
            ax.text(left + PAD_X, row_top - ROW_HEIGHT / 2, badge,
                    ha='left', va='center', fontsize=6.8, fontweight='bold',
                    color=badge_color, zorder=3)
            col_x = left + PAD_X + 1.3
        else:
            col_x = left + PAD_X

        ax.text(col_x, row_top - ROW_HEIGHT / 2, col,
                ha='left', va='center', fontsize=7.0, color='#212121', zorder=3)
        ax.text(left + COL_WIDTH - PAD_X, row_top - ROW_HEIGHT / 2, dtype,
                ha='right', va='center', fontsize=6.2, color='#546E7A',
                style='italic', zorder=3)

        anchors[col] = (cx, row_top - ROW_HEIGHT / 2)

    # Outer border
    border = FancyBboxPatch((left, top - box_h), COL_WIDTH, box_h,
                             boxstyle="round,pad=0.05", linewidth=1.0,
                             edgecolor="#37474F", facecolor='none', zorder=4)
    ax.add_patch(border)

    return anchors, left, top, box_h


# ─────────────────────────────────────────────────────────────────
# Draw all tables, collect bounding boxes
# ─────────────────────────────────────────────────────────────────
table_meta = {}  # name -> (cx, cy, left, top, box_h)
for tname, cols in TABLES.items():
    if tname not in LAYOUT:
        continue
    cx, cy = LAYOUT[tname]
    anchors, left, top, box_h = draw_table(ax, tname, cols, cx, cy)
    table_meta[tname] = (cx, cy, left, top, box_h)


# ─────────────────────────────────────────────────────────────────
# Draw relationship lines (centre → centre with arrow)
# ─────────────────────────────────────────────────────────────────
def table_centre(name):
    cx, cy, left, top, box_h = table_meta[name]
    return cx, top - box_h / 2


for (src, dst, lbl) in RELATIONSHIPS:
    if src not in table_meta or dst not in table_meta:
        continue
    sx, sy = table_centre(src)
    dx, dy = table_centre(dst)
    ax.annotate("",
                 xy=(dx, dy), xytext=(sx, sy),
                 arrowprops=dict(
                     arrowstyle="-|>",
                     color="#546E7A",
                     lw=0.7,
                     connectionstyle="arc3,rad=0.1",
                 ),
                 zorder=0)
    mx, my = (sx + dx) / 2, (sy + dy) / 2
    ax.text(mx, my, lbl, fontsize=4.5, color="#37474F", ha='center', va='center',
            bbox=dict(facecolor='white', edgecolor='none', alpha=0.7, pad=0.5),
            zorder=5)

# ─────────────────────────────────────────────────────────────────
# Legend
# ─────────────────────────────────────────────────────────────────
legend_x, legend_y = 1.5, 6.5
ax.add_patch(plt.Rectangle((legend_x - 0.3, legend_y - 2.0), 12, 2.6,
                             facecolor='white', edgecolor='#90A4AE', lw=0.8, zorder=6))
ax.text(legend_x + 5.7, legend_y + 0.3, "Legend", fontsize=7, fontweight='bold',
        ha='center', color='#263238', zorder=7)
items = [
    (ROW_BG_PK, "#C62828", "PK  Primary Key"),
    (ROW_BG_FK, "#1565C0", "FK  Foreign Key"),
    ("#FAFAFA", "#546E7A", "Regular column"),
]
for i, (bg, tc, label) in enumerate(items):
    ry = legend_y - 0.3 - i * 0.7
    ax.add_patch(plt.Rectangle((legend_x, ry - 0.25), 1.5, 0.5,
                                facecolor=bg, edgecolor='#CFD8DC', lw=0.5, zorder=7))
    ax.text(legend_x + 1.7, ry, label, fontsize=6, va='center', color=tc, zorder=7)

# ─────────────────────────────────────────────────────────────────
# Save
# ─────────────────────────────────────────────────────────────────
out = "/Users/kwizera/sites/laravel_project/e-resto/app/image/erd.png"
plt.tight_layout(pad=0.5)
plt.savefig(out, dpi=220, bbox_inches='tight', facecolor=fig.get_facecolor())
plt.close()
print(f"ERD saved → {out}")
